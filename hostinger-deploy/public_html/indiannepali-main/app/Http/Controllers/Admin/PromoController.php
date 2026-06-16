<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Promo;
use App\Services\AdminData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PromoController extends Controller
{
    public function index(): View
    {
        return view('admin.promos.index', [
            'active' => 'promos',
            'promos' => Promo::orderBy('sort_order')->get(),
            'menuItems' => MenuItem::orderBy('name')->get(['slug', 'name']),
            'offerTypes' => Promo::offerTypes(),
            'ctaTypes' => Promo::ctaTypes(),
            'badges' => AdminData::getNavBadges(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['accent'] = $data['accent'] ?? 'gold';
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = (Promo::max('sort_order') ?? 0) + 1;

        Promo::create($data);

        return back()->with('success', 'Offer created.');
    }

    public function update(Request $request, Promo $promo): RedirectResponse
    {
        $data = $this->validated($request);

        if ($promo->title !== $data['title']) {
            $data['slug'] = $this->uniqueSlug($data['title'], $promo->id);
        }

        $data['is_active'] = $request->boolean('is_active');

        $promo->update($data);

        return back()->with('success', 'Offer updated.');
    }

    public function destroy(Promo $promo): RedirectResponse
    {
        $promo->delete();

        return back()->with('success', 'Offer deleted.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'badge' => 'required|string|max:40',
            'title' => 'required|string|max:120',
            'detail' => 'required|string|max:500',
            'price_label' => 'required|string|max:40',
            'accent' => 'nullable|string|max:20',
            'offer_type' => 'required|in:combo_meal,spend_save,reservation_perk,limited_time',
            'cta_type' => 'required|in:menu,order_item,reserve',
            'cta_label' => 'nullable|string|max:40',
            'menu_item_slug' => 'nullable|string|max:80|exists:menu_items,slug',
            'terms' => 'nullable|string|max:500',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'min_order_amount' => 'nullable|numeric|min:0',
            'min_party_size' => 'nullable|integer|min:1|max:30',
            'is_active' => 'boolean',
        ]);

        if ($data['cta_type'] !== Promo::CTA_ORDER_ITEM) {
            $data['menu_item_slug'] = null;
        }

        if ($data['offer_type'] !== Promo::TYPE_SPEND_SAVE) {
            $data['min_order_amount'] = null;
        }

        if ($data['offer_type'] !== Promo::TYPE_RESERVATION && $data['cta_type'] !== Promo::CTA_RESERVE) {
            $data['min_party_size'] = null;
        }

        return $data;
    }

    private function uniqueSlug(string $title, ?int $exceptId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (Promo::where('slug', $slug)
            ->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))
            ->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
