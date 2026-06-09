<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public static function taxRate(): float
    {
        return (float) Setting::get('tax_rate', 0.0875);
    }

    public static function deliveryFeeAmount(): float
    {
        return (float) Setting::get('delivery_fee', 3.99);
    }

    public static function freeDeliveryMin(): float
    {
        return (float) Setting::get('free_delivery_min', 40);
    }

    public static function resolveCart(): array
    {
        $sessionCart = session('cart', []);
        $items = [];
        $count = 0;
        $subtotal = 0;

        $menuItems = MenuItem::query()
            ->with('category')
            ->whereIn('slug', array_keys($sessionCart))
            ->where('is_available', true)
            ->get()
            ->keyBy('slug');

        foreach ($sessionCart as $slug => $qty) {
            $item = $menuItems->get($slug);
            if (! $item || $qty < 1) {
                continue;
            }
            $legacy = $item->toLegacy();
            $legacy['qty'] = (int) $qty;
            $items[] = $legacy;
            $count += (int) $qty;
            $subtotal += (float) $item->price * $qty;
        }

        return [
            'cartItems' => $items,
            'cartCount' => $count,
            'cartSubtotal' => round($subtotal, 2),
        ];
    }

    public static function deliveryFee(float $subtotal, string $mode = 'delivery'): float
    {
        if ($mode !== 'delivery') {
            return 0;
        }

        return $subtotal >= self::freeDeliveryMin() ? 0 : self::deliveryFeeAmount();
    }

    public function add(Request $request): RedirectResponse
    {
        $request->validate(['item_id' => 'required|string']);

        $item = MenuItem::query()
            ->where('slug', $request->input('item_id'))
            ->where('is_available', true)
            ->first();

        if (! $item) {
            return back()->with('error', 'Item not found or unavailable.');
        }

        $cart = session('cart', []);
        $cart[$item->slug] = ($cart[$item->slug] ?? 0) + 1;
        session(['cart' => $cart]);

        return back()->with('success', $item->name.' added to your order.');
    }

    public function update(Request $request, string $itemId): RedirectResponse
    {
        $request->validate(['qty' => 'required|integer|min:0']);

        $cart = session('cart', []);
        $qty = (int) $request->input('qty');

        if ($qty === 0) {
            unset($cart[$itemId]);
        } elseif (MenuItem::where('slug', $itemId)->where('is_available', true)->exists()) {
            $cart[$itemId] = $qty;
        }

        session(['cart' => $cart]);

        return back();
    }

    public function remove(string $itemId): RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[$itemId]);
        session(['cart' => $cart]);

        return back();
    }

    public function show(): View|RedirectResponse
    {
        $cart = self::resolveCart();

        if ($cart['cartCount'] === 0) {
            return redirect()->route('menu')->with('info', 'Your bag is empty.');
        }

        return view('customer.checkout.index', array_merge($cart, [
            'mode' => session('order_mode', 'delivery'),
        ]));
    }
}
