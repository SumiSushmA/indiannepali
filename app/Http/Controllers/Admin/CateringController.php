<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CateringInquiry;
use App\Models\CateringPackage;
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
            'packages' => CateringPackage::orderBy('sort_order')->get(),
            'badges' => AdminData::getNavBadges(),
        ]);
    }

    public function updateStatus(Request $request, CateringInquiry $catering): RedirectResponse
    {
        $request->validate(['status' => 'required|in:New,Quoted,In conversation,Booked']);

        $catering->update(['status' => $request->input('status')]);

        return back()->with('success', 'Catering inquiry updated.');
    }
}
