<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiftAmount;
use App\Services\AdminData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GiftAmountController extends Controller
{
    public function index(): View
    {
        return view('admin.gift-amounts.index', [
            'active' => 'gift-amounts',
            'amounts' => GiftAmount::orderBy('sort_order')->get(),
            'badges' => AdminData::getNavBadges(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:1|max:1000',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = (GiftAmount::max('sort_order') ?? 0) + 1;

        GiftAmount::create($data);

        return back()->with('success', 'Gift amount added.');
    }

    public function update(Request $request, GiftAmount $giftAmount): RedirectResponse
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:1|max:1000',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $giftAmount->update($data);

        return back()->with('success', 'Gift amount updated.');
    }

    public function destroy(GiftAmount $giftAmount): RedirectResponse
    {
        $giftAmount->delete();

        return back()->with('success', 'Gift amount deleted.');
    }
}
