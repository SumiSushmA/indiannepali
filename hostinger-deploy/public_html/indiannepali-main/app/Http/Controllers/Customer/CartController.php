<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Promo;
use App\Models\Setting;
use App\Support\CateringCart;
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
        $settingMin = (float) Setting::get('free_delivery_min', 40);
        $promoMin = Promo::activeFreeDeliveryMinimum();

        if ($promoMin !== null) {
            return min($settingMin, $promoMin);
        }

        return $settingMin;
    }

    public static function qualifiesForFreeDelivery(float $subtotal, string $mode = 'delivery'): bool
    {
        return $mode === 'delivery' && $subtotal >= self::freeDeliveryMin();
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

        foreach (CateringCart::lines() as $line) {
            $items[] = $line;
            $count += (int) $line['qty'];
            $subtotal += (float) $line['price'] * $line['qty'];
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

    /** @return array{min: float, offer: ?\App\Models\Promo, qualifies: bool} */
    public static function freeDeliveryStatus(float $subtotal, string $mode = 'delivery'): array
    {
        $min = self::freeDeliveryMin();
        $offer = Promo::activeFreeDeliveryOffer();

        return [
            'min' => $min,
            'offer' => $offer,
            'qualifies' => self::qualifiesForFreeDelivery($subtotal, $mode),
        ];
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

        $qty = (int) $request->input('qty');

        if (CateringCart::isCateringLine($itemId)) {
            if ($itemId === CateringCart::PER_PERSON_ID) {
                if ($qty === 0 || $qty < \App\Data\CateringMenu::MIN_GUESTS) {
                    CateringCart::removePerPerson();
                } else {
                    $cart = CateringCart::all();
                    if ($cart['per_person']) {
                        $cart['per_person']['guest_count'] = $qty;
                        CateringCart::save($cart);
                    }
                }
            } elseif (str_starts_with($itemId, 'catering-tray:')) {
                $slug = substr($itemId, strlen('catering-tray:'));
                CateringCart::updateTray($slug, $qty);
            }

            session(['cart' => session('cart', [])]);

            return $this->cartRedirect($request);
        }

        $cart = session('cart', []);

        if ($qty === 0) {
            unset($cart[$itemId]);
        } elseif (MenuItem::where('slug', $itemId)->where('is_available', true)->exists()) {
            $cart[$itemId] = $qty;
        }

        session(['cart' => $cart]);

        return $this->cartRedirect($request);
    }

    public function remove(Request $request, string $itemId): RedirectResponse
    {
        if (CateringCart::isCateringLine($itemId)) {
            if ($itemId === CateringCart::PER_PERSON_ID) {
                CateringCart::removePerPerson();
            } elseif (str_starts_with($itemId, 'catering-tray:')) {
                $slug = substr($itemId, strlen('catering-tray:'));
                CateringCart::updateTray($slug, 0);
            }

            return $this->cartRedirect($request);
        }

        $cart = session('cart', []);
        unset($cart[$itemId]);
        session(['cart' => $cart]);

        return $this->cartRedirect($request);
    }

    private function cartRedirect(Request $request): RedirectResponse
    {
        if (self::resolveCart()['cartCount'] === 0) {
            return redirect()->route('menu')->with('info', 'Your bag is empty.');
        }

        if ($request->input('redirect') === 'checkout') {
            return redirect()->route('checkout');
        }

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
