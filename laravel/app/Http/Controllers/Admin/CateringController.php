<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CateringInquiry;
use App\Services\AdminData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CateringController extends Controller
{
    public function index(): View
    {
        return view('admin.catering.index', [
            'active' => 'catering',
            'catering' => AdminData::getCatering(),
            'badges' => AdminData::getNavBadges(),
        ]);
    }

    public function updateStatus(Request $request, CateringInquiry $catering): RedirectResponse
    {
        $request->validate(['status' => 'required|in:New,Quoted,In conversation,Booked']);

        $catering->update(['status' => $request->input('status')]);

        return back()->with('success', 'Catering inquiry updated.');
    }

    public function updateQuote(Request $request, CateringInquiry $catering): RedirectResponse
    {
        $request->validate(['quoted_value' => 'required|numeric|min:0']);

        $catering->update(['quoted_value' => $request->input('quoted_value')]);

        return back()->with('success', 'Quote updated for '.$catering->customer_name.'.');
    }
}
