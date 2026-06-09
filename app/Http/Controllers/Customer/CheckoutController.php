<?php

namespace App\Http\Controllers\Customer;

use App\Contracts\ToastPaymentGateway;
use App\Data\Toast\OrderChargeRequest;
use App\Exceptions\ToastPaymentException;
use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(private ToastPaymentGateway $payments) {}

    public function index(Request $request): View|RedirectResponse
    {
        $cart = CartController::resolveCart();

        if ($cart['cartCount'] === 0) {
            return redirect()->route('menu')->with('info', 'Your bag is empty.');
        }

        $mode = $request->input('mode', session('order_mode', 'delivery'));
        if (! in_array($mode, ['delivery', 'pickup'], true)) {
            $mode = 'delivery';
        }

        $subtotal = $cart['cartSubtotal'];
        $tax = round($subtotal * CartController::taxRate(), 2);
        $deliveryFee = CartController::deliveryFee($subtotal, $mode);
        $tipRate = (float) $request->input('tip', session('checkout_tip', 0.18));
        $tipAmount = round($subtotal * $tipRate, 2);

        return view('customer.checkout.index', array_merge($cart, [
            'mode' => $mode,
            'tax' => $tax,
            'deliveryFee' => $deliveryFee,
            'tipRate' => $tipRate,
            'tipAmount' => $tipAmount,
            'total' => round($subtotal + $tax + $deliveryFee + $tipAmount, 2),
        ]));
    }

    public function store(Request $request): RedirectResponse
    {
        $cart = CartController::resolveCart();

        if ($cart['cartCount'] === 0) {
            return redirect()->route('menu')->with('info', 'Your bag is empty.');
        }

        $request->validate([
            'name' => 'required|string|max:120',
            'phone' => 'required|string|max:30',
            'email' => 'required|email|max:120',
            'mode' => 'required|in:delivery,pickup',
            'address' => 'required_if:mode,delivery|nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
            'tip' => 'nullable|numeric|min:0|max:1',
            'card_number' => 'required|string|max:30',
            'card_expiry' => 'nullable|string|max:10',
            'card_cvc' => 'nullable|string|max:4',
        ]);

        $mode = $request->input('mode');
        $subtotal = $cart['cartSubtotal'];
        $tipRate = (float) ($request->input('tip') ?? 0.18);
        $tax = round($subtotal * CartController::taxRate(), 2);
        $deliveryFee = CartController::deliveryFee($subtotal, $mode);
        $tipAmount = round($subtotal * $tipRate, 2);
        $total = round($subtotal + $tax + $deliveryFee + $tipAmount, 2);

        $menuItems = MenuItem::query()
            ->whereIn('slug', array_column($cart['cartItems'], 'id'))
            ->get()
            ->keyBy('slug');

        $chargeItems = [];
        foreach ($cart['cartItems'] as $line) {
            $menuItem = $menuItems->get($line['id']);
            $chargeItems[] = [
                'id' => $line['id'],
                'name' => $line['name'],
                'price' => $line['price'],
                'qty' => $line['qty'],
                'toast_pos_id' => $menuItem?->toast_pos_id,
            ];
        }

        try {
            $payment = $this->payments->chargeOrder(new OrderChargeRequest(
                items: $chargeItems,
                customerName: $request->input('name'),
                customerEmail: $request->input('email'),
                customerPhone: $request->input('phone'),
                fulfillmentType: $mode,
                address: $request->input('address'),
                notes: $request->input('notes'),
                subtotal: $subtotal,
                tax: $tax,
                deliveryFee: $deliveryFee,
                tip: $tipAmount,
                total: $total,
                cardNumber: $request->input('card_number'),
                cardExpiry: $request->input('card_expiry'),
                cardCvc: $request->input('card_cvc'),
                clientIp: $request->ip() ?? '127.0.0.1',
            ));
        } catch (ToastPaymentException $e) {
            return back()->withInput()->withErrors(['payment' => $e->getMessage()]);
        }

        if (! $payment->success) {
            return back()->withInput()->withErrors(['payment' => $payment->error ?? 'Payment could not be processed.']);
        }

        $order = DB::transaction(function () use ($request, $cart, $mode, $subtotal, $tax, $deliveryFee, $tipAmount, $tipRate, $total, $payment, $menuItems) {
            $orderNumber = 'NK-'.(4850 + Order::count() + 1);

            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_name' => $request->input('name'),
                'customer_email' => $request->input('email'),
                'customer_phone' => $request->input('phone'),
                'fulfillment_type' => $mode,
                'address' => $request->input('address'),
                'delivery_notes' => $request->input('notes'),
                'channel' => 'Website',
                'status' => 'New',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'delivery_fee' => $deliveryFee,
                'tip' => $tipAmount,
                'tip_rate' => $tipRate,
                'total' => $total,
                'payment_status' => $payment->status,
                'payment_provider' => $payment->provider,
                'toast_order_guid' => $payment->toastOrderGuid,
                'toast_payment_guid' => $payment->toastPaymentGuid,
                'payment_reference' => $payment->paymentReference,
                'card_last4' => $payment->cardLast4,
                'card_brand' => $payment->cardBrand,
                'placed_at' => now(),
            ]);

            foreach ($cart['cartItems'] as $line) {
                $menuItem = $menuItems->get($line['id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItem?->id,
                    'item_name' => $line['name'],
                    'unit_price' => $line['price'],
                    'quantity' => $line['qty'],
                    'line_total' => round($line['price'] * $line['qty'], 2),
                ]);
            }

            return $order;
        });

        session([
            'order_mode' => $mode,
            'checkout_tip' => $tipRate,
            'last_order' => [
                'number' => $order->order_number,
                'mode' => $mode,
                'total' => (float) $order->total,
                'name' => $order->customer_name,
                'payment_provider' => $payment->provider,
                'payment_reference' => $payment->paymentReference,
            ],
            'cart' => [],
        ]);

        return redirect()->route('checkout.confirmed');
    }

    public function confirmed(): View|RedirectResponse
    {
        $order = session('last_order');

        if (! $order) {
            return redirect()->route('home');
        }

        return view('customer.checkout.confirmed', ['order' => $order]);
    }
}
