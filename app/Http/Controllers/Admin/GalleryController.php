<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Services\AdminData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = GalleryCategory::with(['images' => fn ($q) => $q->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        $activeCategory = $categories->firstWhere('slug', $request->query('category'))
            ?? $categories->first();

        return view('admin.gallery.index', [
            'active' => 'gallery',
            'galleryCats' => $categories,
            'activeCategory' => $activeCategory,
            'badges' => AdminData::getNavBadges(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'gallery_category_id' => 'required|exists:gallery_categories,id',
            'caption' => 'required|string|max:120',
            'image' => 'required|image|max:4096',
        ]);

        $categoryId = $data['gallery_category_id'];
        unset($data['gallery_category_id']);

        $data['image_path'] = $request->file('image')->store('gallery', 'public');
        $data['gallery_category_id'] = $categoryId;
        $data['sort_order'] = (GalleryImage::where('gallery_category_id', $categoryId)->max('sort_order') ?? 0) + 1;

        GalleryImage::create($data);

        $category = GalleryCategory::find($categoryId);

        return redirect()
            ->route('admin.gallery.index', ['category' => $category?->slug])
            ->with('success', 'Image uploaded.');
    }

    public function update(Request $request, GalleryImage $galleryImage): RedirectResponse
    {
        $data = $request->validate([
            'caption' => 'required|string|max:120',
        ]);

        $galleryImage->update($data);

        return back()->with('success', 'Caption updated.');
    }

    public function destroy(GalleryImage $galleryImage): RedirectResponse
    {
        $slug = $galleryImage->category?->slug;
        $galleryImage->delete();

        return redirect()
            ->route('admin.gallery.index', ['category' => $slug])
            ->with('success', 'Image deleted.');
    }
}
