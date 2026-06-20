<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiftAmount;
use App\Services\AdminData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GiftAmountController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.gift-cards.index')->withFragment('amounts');
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

        return redirect()->route('admin.gift-cards.index')->withFragment('amounts')->with('success', 'Gift amount added.');
    }

    public function update(Request $request, GiftAmount $giftAmount): RedirectResponse
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:1|max:1000',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $giftAmount->update($data);

        return redirect()->route('admin.gift-cards.index')->withFragment('amounts')->with('success', 'Gift amount updated.');
    }

    public function destroy(GiftAmount $giftAmount): RedirectResponse
    {
        $giftAmount->delete();

        return redirect()->route('admin.gift-cards.index')->withFragment('amounts')->with('success', 'Gift amount deleted.');
    }
}
