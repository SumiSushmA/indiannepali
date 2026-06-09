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
        return view('admin.reservations.index', [
            'active' => 'reservations',
            'reservations' => AdminData::getReservations(),
            'calCounts' => AdminData::getCalCounts(),
            'badges' => AdminData::getNavBadges(),
        ]);
    }

    public function updateStatus(Request $request, Reservation $reservation): RedirectResponse
    {
        $request->validate(['status' => 'required|in:'.implode(',', AdminData::getResStatuses())]);

        $reservation->update(['status' => $request->input('status')]);

        return back()->with('success', 'Reservation '.$reservation->reference.' updated.');
    }
}
