<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CateringInquiry;
use App\Services\RestaurantData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CateringController extends Controller
{
    public const MIN_GUEST_COUNT = 20;

    public function create(): View
    {
        return view('customer.catering.create', [
            'packages' => RestaurantData::cateringPackages(),
            'submitted' => session('catering_submitted', false),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:120',
            'phone' => 'required|string|max:30',
            'event_type' => 'required|string|max:60',
            'event_date' => 'required|date|after:today',
            'guest_count' => 'required|integer|min:'.self::MIN_GUEST_COUNT,
            'message' => 'nullable|string|max:1000',
        ], [
            'guest_count.min' => 'Catering is available for groups of '.self::MIN_GUEST_COUNT.' people or more.',
        ]);

        CateringInquiry::create([
            'reference' => 'C-'.(510 + CateringInquiry::count()),
            'customer_name' => $request->input('name'),
            'customer_email' => $request->input('email'),
            'customer_phone' => $request->input('phone'),
            'event_type' => $request->input('event_type'),
            'event_date' => $request->input('event_date'),
            'guest_count' => $request->input('guest_count'),
            'message' => $request->input('message'),
            'status' => 'New',
        ]);

        return redirect()->route('catering')->with('catering_submitted', true);
    }
}
