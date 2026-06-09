<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            'badges' => AdminData::getNavBadges(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'badge' => 'required|string|max:40',
            'title' => 'required|string|max:120',
            'detail' => 'required|string|max:500',
            'price_label' => 'required|string|max:40',
            'accent' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['accent'] = $data['accent'] ?? 'gold';
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = (Promo::max('sort_order') ?? 0) + 1;

        Promo::create($data);

        return back()->with('success', 'Promo created.');
    }

    public function update(Request $request, Promo $promo): RedirectResponse
    {
        $data = $request->validate([
            'badge' => 'required|string|max:40',
            'title' => 'required|string|max:120',
            'detail' => 'required|string|max:500',
            'price_label' => 'required|string|max:40',
            'accent' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        if ($promo->title !== $data['title']) {
            $data['slug'] = $this->uniqueSlug($data['title'], $promo->id);
        }

        $data['is_active'] = $request->boolean('is_active');

        $promo->update($data);

        return back()->with('success', 'Promo updated.');
    }

    public function destroy(Promo $promo): RedirectResponse
    {
        $promo->delete();

        return back()->with('success', 'Promo deleted.');
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
