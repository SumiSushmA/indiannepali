<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiftCard;
use App\Models\GiftCardDesign;
use App\Services\AdminData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GiftCardController extends Controller
{
    public function index(): View
    {
        return view('admin.gift-cards.index', [
            'active' => 'giftcards',
            'giftCards' => AdminData::getGiftCards(),
            'giftStats' => AdminData::getGiftStats(),
            'giftSales' => AdminData::getGiftSales(),
            'designs' => GiftCardDesign::where('is_active', true)->get(),
            'badges' => AdminData::getNavBadges(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'gift_card_design_id' => 'required|exists:gift_card_designs,id',
            'face_value' => 'required|numeric|min:10|max:1000',
            'recipient_name' => 'required|string|max:120',
            'sender_name' => 'nullable|string|max:120',
            'message' => 'nullable|string|max:500',
        ]);

        $code = 'NK-'.strtoupper(Str::random(4)).'-'.random_int(1000, 9999);

        GiftCard::create([
            'code' => $code,
            'gift_card_design_id' => $data['gift_card_design_id'],
            'face_value' => $data['face_value'],
            'balance' => $data['face_value'],
            'status' => 'Active',
            'recipient_name' => $data['recipient_name'],
            'sender_name' => $data['sender_name'] ?? null,
            'message' => $data['message'] ?? null,
            'delivery_method' => 'email',
            'channel' => 'Manual',
            'issued_at' => now(),
        ]);

        return back()->with('success', 'Gift card issued ('.$code.').');
    }

    public function update(Request $request, GiftCard $giftCard): RedirectResponse
    {
        $data = $request->validate([
            'balance' => 'required|numeric|min:0|max:10000',
            'status' => 'required|in:Active,Partially used,Redeemed',
        ]);

        $giftCard->update($data);

        return back()->with('success', 'Gift card updated.');
    }
}
