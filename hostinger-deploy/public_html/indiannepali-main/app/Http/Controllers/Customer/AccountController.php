<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(Request $request): View
    {
        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();
        $tab = $request->input('tab', 'orders');

        if (! in_array($tab, ['orders', 'reservations', 'catering', 'messages', 'payments', 'reviews', 'profile', 'password'], true)) {
            $tab = 'orders';
        }

        return view('customer.account.index', [
            'customer' => $customer,
            'tab' => $tab,
            'orders' => $customer->linkedOrders()->with('items')->limit(50)->get(),
            'reservations' => $customer->linkedReservations()->limit(50)->get(),
            'cateringInquiries' => $customer->linkedCateringInquiries()->with('package')->limit(50)->get(),
            'contactMessages' => $customer->linkedContactMessages()->limit(50)->get(),
            'payments' => $customer->linkedOrders()
                ->whereNotNull('payment_reference')
                ->limit(50)
                ->get(),
            'reviews' => Review::query()
                ->where('customer_id', $customer->id)
                ->latest()
                ->limit(20)
                ->get(),
        ]);
    }

    public function storeReview(Request $request): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();

        $data = $request->validate([
            'stars' => 'required|integer|min:1|max:5',
            'body' => 'required|string|max:1000',
        ]);

        Review::create([
            'customer_id' => $customer->id,
            'author_name' => $customer->name,
            'stars' => $data['stars'],
            'body' => $data['body'],
            'source_tag' => 'Customer',
            'is_featured' => true,
            'sort_order' => (Review::max('sort_order') ?? 0) + 1,
        ]);

        return redirect()
            ->route('account.index', ['tab' => 'reviews'])
            ->with('success', 'Thanks! Your review has been posted.');
    }

    public function showOrder(string $orderNumber): View|RedirectResponse
    {
        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();

        $order = $this->findCustomerOrder($customer, $orderNumber);

        if (! $order) {
            return redirect()->route('account.index')->withErrors(['order' => 'Order not found.']);
        }

        $order->load('items');

        return view('customer.account.order', [
            'customer' => $customer,
            'order' => $order,
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'phone' => 'nullable|string|max:30',
        ]);

        $customer->update([
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
        ]);

        return redirect()
            ->route('account.index', ['tab' => 'profile'])
            ->with('success', 'Profile updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();

        $data = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (! Hash::check($data['current_password'], $customer->password)) {
            return redirect()
                ->route('account.index', ['tab' => 'password'])
                ->withErrors(['current_password' => 'Your current password is incorrect.']);
        }

        $customer->update(['password' => $data['password']]);

        return redirect()
            ->route('account.index', ['tab' => 'password'])
            ->with('success', 'Password updated successfully.');
    }

    private function findCustomerOrder(Customer $customer, string $orderNumber): ?Order
    {
        return Order::query()
            ->where('order_number', $orderNumber)
            ->where(function ($query) use ($customer) {
                $query->where('customer_id', $customer->id)
                    ->orWhereRaw('LOWER(customer_email) = ?', [strtolower($customer->email)]);
            })
            ->first();
    }
}
