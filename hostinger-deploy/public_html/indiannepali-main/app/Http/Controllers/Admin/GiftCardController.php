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
        $designs = GiftCardDesign::query()->orderBy('name')->get();

        return view('admin.gift-cards.index', [
            'active' => 'giftcards',
            'giftCards' => AdminData::getGiftCards(),
            'giftStats' => AdminData::getGiftStats(),
            'giftSales' => AdminData::getGiftSales(),
            'designs' => $designs,
            'activeDesigns' => $designs->where('is_active', true)->values(),
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

    public function storeDesign(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:80',
            'subtitle' => 'nullable|string|max:120',
            'bg_start' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'bg_mid' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'bg_end' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'text_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'is_active' => 'nullable|boolean',
        ]);

        $slugBase = Str::slug($data['name']);
        $slug = $slugBase;
        $count = 2;
        while (GiftCardDesign::query()->where('slug', $slug)->exists()) {
            $slug = $slugBase.'-'.$count;
            $count++;
        }

        GiftCardDesign::create([
            'slug' => $slug,
            'name' => $data['name'],
            'subtitle' => $data['subtitle'] ?? null,
            'accent' => 'custom',
            'bg_start' => strtoupper($data['bg_start']),
            'bg_mid' => strtoupper($data['bg_mid']),
            'bg_end' => strtoupper($data['bg_end']),
            'text_color' => strtoupper($data['text_color']),
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        return back()->with('success', 'Gift card design added.');
    }

    public function updateDesign(Request $request, GiftCardDesign $design): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:80',
            'subtitle' => 'nullable|string|max:120',
            'bg_start' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'bg_mid' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'bg_end' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'text_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'is_active' => 'nullable|boolean',
        ]);

        $design->update([
            'name' => $data['name'],
            'subtitle' => $data['subtitle'] ?? null,
            'bg_start' => strtoupper($data['bg_start']),
            'bg_mid' => strtoupper($data['bg_mid']),
            'bg_end' => strtoupper($data['bg_end']),
            'text_color' => strtoupper($data['text_color']),
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return back()->with('success', 'Gift card design updated.');
    }

    public function destroyDesign(GiftCardDesign $design): RedirectResponse
    {
        if ($design->giftCards()->exists()) {
            return back()->with('error', 'Cannot delete this design because it is already used by gift cards.');
        }

        $design->delete();

        return back()->with('success', 'Gift card design deleted.');
    }
}
