<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Promo;
use App\Services\RestaurantData;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PromoController extends Controller
{
    public function index(): View
    {
        return view('customer.promos.index', [
            'promos' => RestaurantData::promos(),
        ]);
    }

    public function order(Promo $promo): RedirectResponse
    {
        if ($promo->cta_type !== Promo::CTA_ORDER_ITEM || ! $promo->menu_item_slug) {
            $action = $promo->primaryAction();

            return redirect($action['href'] ?? route('menu'));
        }

        $item = MenuItem::query()
            ->where('slug', $promo->menu_item_slug)
            ->where('is_available', true)
            ->first();

        if (! $item) {
            return redirect()
                ->route('promos')
                ->with('error', 'That item is unavailable right now. Please browse the menu.');
        }

        $cart = session('cart', []);
        $cart[$item->slug] = ($cart[$item->slug] ?? 0) + 1;
        session(['cart' => $cart]);

        return redirect()
            ->route('checkout')
            ->with('success', $item->name.' added — ready to checkout.');
    }
}
