<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CateringPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CateringPackageController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'guest_range' => 'required|string|max:40',
            'price_label' => 'required|string|max:40',
            'items' => 'required|string|max:2000',
            'is_popular' => 'boolean',
        ]);

        $items = array_values(array_filter(array_map('trim', explode("\n", $data['items']))));
        unset($data['items']);

        CateringPackage::create([
            ...$data,
            'items' => $items,
            'is_popular' => $request->boolean('is_popular'),
            'sort_order' => (CateringPackage::max('sort_order') ?? 0) + 1,
        ]);

        return back()->with('success', 'Catering package created.');
    }

    public function update(Request $request, CateringPackage $cateringPackage): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'guest_range' => 'required|string|max:40',
            'price_label' => 'required|string|max:40',
            'items' => 'required|string|max:2000',
            'is_popular' => 'boolean',
        ]);

        $items = array_values(array_filter(array_map('trim', explode("\n", $data['items']))));
        unset($data['items']);

        $cateringPackage->update([
            ...$data,
            'items' => $items,
            'is_popular' => $request->boolean('is_popular'),
        ]);

        return back()->with('success', 'Catering package updated.');
    }

    public function destroy(CateringPackage $cateringPackage): RedirectResponse
    {
        $cateringPackage->delete();

        return back()->with('success', 'Catering package deleted.');
    }
}
