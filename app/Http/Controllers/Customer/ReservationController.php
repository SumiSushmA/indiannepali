<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function create(): View
    {
        $dates = [];
        for ($i = 1; $i <= 8; $i++) {
            $d = now()->addDays($i);
            $dates[] = [
                'value' => $d->toDateString(),
                'weekday' => $d->format('D'),
                'day' => $d->format('j'),
                'month' => $d->format('M'),
            ];
        }

        $times = ['17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00'];

        return view('customer.reserve.create', [
            'dates' => $dates,
            'times' => $times,
            'reservation' => session('reservation'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'party' => 'required|integer|min:1|max:20',
            'date' => 'required|date|after:today',
            'time' => 'required|string|max:10',
            'name' => 'required|string|max:120',
            'phone' => 'required|string|max:30',
            'email' => 'required|email|max:120',
            'occasion' => 'nullable|string|max:60',
            'notes' => 'nullable|string|max:500',
        ]);

        $reference = 'R-'.(2100 + Reservation::count());

        $reservation = Reservation::create([
            'reference' => $reference,
            'party_size' => $request->input('party'),
            'reserved_date' => $request->input('date'),
            'reserved_time' => $request->input('time'),
            'customer_name' => $request->input('name'),
            'customer_email' => $request->input('email'),
            'customer_phone' => $request->input('phone'),
            'occasion' => $request->input('occasion') ?: '—',
            'notes' => $request->input('notes'),
            'status' => 'Confirmed',
            'table_number' => 'T'.rand(1, 18),
        ]);

        session(['reservation' => [
            'id' => $reservation->reference,
            'party' => $reservation->party_size,
            'date' => $reservation->reserved_date->format('l, F j'),
            'time' => $reservation->reserved_time,
            'name' => $reservation->customer_name,
        ]]);

        return redirect()->route('reserve')->with('success', 'Your table is reserved!');
    }
}
