<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Services\AdminData;
use App\Services\RestaurantData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $menu = RestaurantData::getMenu();
        $allItems = MenuItem::with('category')->orderBy('sort_order')->get();

        return view('admin.menu.index', [
            'active' => 'menu',
            'menu' => [
                'categories' => $menu['categories'],
                'items' => $allItems->map(fn (MenuItem $item) => array_merge($item->toLegacy(), [
                    'available' => $item->is_available,
                    'pos_id' => $item->toast_pos_id ?? 'TST-'.$item->slug.$item->price,
                ]))->all(),
            ],
            'badges' => AdminData::getNavBadges(),
        ]);
    }

    public function create(): View
    {
        return view('admin.menu.create', [
            'active' => 'menu',
            'categories' => MenuCategory::orderBy('sort_order')->get(),
            'badges' => AdminData::getNavBadges(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'menu_category_id' => 'required|exists:menu_categories,id',
            'name' => 'required|string|max:120',
            'price' => 'required|numeric|min:0|max:999',
            'description' => 'nullable|string|max:1000',
            'is_veg' => 'boolean',
            'spice_level' => 'nullable|integer|min:0|max:5',
            'is_popular' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['is_veg'] = $request->boolean('is_veg');
        $data['is_popular'] = $request->boolean('is_popular');
        $data['spice_level'] = $data['spice_level'] ?? 0;
        $data['sort_order'] = (MenuItem::max('sort_order') ?? 0) + 1;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('menu', 'public');
            $data['image_label'] = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME);
        }

        MenuItem::create($data);

        return redirect()->route('admin.menu.index')->with('success', 'Menu item created.');
    }

    public function edit(MenuItem $menuItem): View
    {
        return view('admin.menu.edit', [
            'active' => 'menu',
            'item' => $menuItem,
            'categories' => MenuCategory::orderBy('sort_order')->get(),
            'badges' => AdminData::getNavBadges(),
        ]);
    }

    public function update(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $data = $request->validate([
            'menu_category_id' => 'required|exists:menu_categories,id',
            'name' => 'required|string|max:120',
            'price' => 'required|numeric|min:0|max:999',
            'description' => 'nullable|string|max:1000',
            'is_veg' => 'boolean',
            'spice_level' => 'nullable|integer|min:0|max:5',
            'is_popular' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($menuItem->name !== $data['name']) {
            $data['slug'] = $this->uniqueSlug($data['name'], $menuItem->id);
        }

        $data['is_veg'] = $request->boolean('is_veg');
        $data['is_popular'] = $request->boolean('is_popular');
        $data['spice_level'] = $data['spice_level'] ?? 0;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('menu', 'public');
            $data['image_label'] = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME);
        }

        $menuItem->update($data);

        return redirect()->route('admin.menu.index')->with('success', 'Menu item updated.');
    }

    public function destroy(MenuItem $menuItem): RedirectResponse
    {
        $menuItem->delete();

        return redirect()->route('admin.menu.index')->with('success', 'Menu item deleted.');
    }

    public function toggleAvailability(MenuItem $menuItem): RedirectResponse
    {
        $menuItem->update(['is_available' => ! $menuItem->is_available]);

        return back()->with('success', $menuItem->name.' is now '.($menuItem->is_available ? 'available' : 'unavailable').'.');
    }

    private function uniqueSlug(string $name, ?int $exceptId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (MenuItem::where('slug', $slug)
            ->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))
            ->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
