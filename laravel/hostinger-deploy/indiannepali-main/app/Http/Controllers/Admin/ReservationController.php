<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Services\AdminData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(): View
    {
        $now = now();
        $monthStart = $now->copy()->startOfMonth();

        return view('admin.reservations.index', [
            'active' => 'reservations',
            'reservations' => AdminData::getReservations(),
            'resStatuses' => AdminData::getResStatuses(),
            'calCounts' => AdminData::getCalCounts(),
            'monthLabel' => $monthStart->format('F Y'),
            'firstDay' => (int) $monthStart->format('w'),
            'daysInMonth' => (int) $monthStart->format('t'),
            'today' => (int) $now->format('j'),
            'badges' => AdminData::getNavBadges(),
        ]);
    }

    public function updateStatus(Request $request, Reservation $reservation): RedirectResponse
    {
        $request->validate(['status' => 'required|in:'.implode(',', AdminData::getResStatuses())]);

        $reservation->update(['status' => $request->input('status')]);

        return back()->with('success', 'Reservation '.$reservation->reference.' updated.');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'party' => 'required|integer|min:1|max:20',
            'date' => 'required|date',
            'time' => 'required|string|max:10',
            'name' => 'required|string|max:120',
            'phone' => 'required|string|max:30',
            'email' => 'required|email|max:120',
            'occasion' => 'nullable|string|max:60',
            'notes' => 'nullable|string|max:500',
            'status' => 'nullable|in:'.implode(',', AdminData::getResStatuses()),
            'table_number' => 'nullable|string|max:20',
        ]);

        $reference = 'R-'.(2100 + Reservation::count() + 1);

        Reservation::create([
            'reference' => $reference,
            'party_size' => $data['party'],
            'reserved_date' => $data['date'],
            'reserved_time' => $data['time'],
            'customer_name' => $data['name'],
            'customer_email' => $data['email'],
            'customer_phone' => $data['phone'],
            'occasion' => $data['occasion'] ?: '—',
            'notes' => $data['notes'] ?? null,
            'status' => $data['status'] ?? 'Confirmed',
            'table_number' => $data['table_number'] ?: 'T'.rand(1, 18),
        ]);

        return back()->with('success', 'Reservation created.');
    }
}
