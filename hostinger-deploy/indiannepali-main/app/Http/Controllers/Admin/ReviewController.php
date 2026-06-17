<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\AdminData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        return view('admin.reviews.index', [
            'active' => 'reviews',
            'reviews' => Review::orderBy('sort_order')->get(),
            'badges' => AdminData::getNavBadges(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'author_name' => 'required|string|max:80',
            'stars' => 'required|integer|min:1|max:5',
            'body' => 'required|string|max:1000',
            'source_tag' => 'required|string|max:40',
            'is_featured' => 'boolean',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['sort_order'] = (Review::max('sort_order') ?? 0) + 1;

        Review::create($data);

        return back()->with('success', 'Review added.');
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        $data = $request->validate([
            'author_name' => 'required|string|max:80',
            'stars' => 'required|integer|min:1|max:5',
            'body' => 'required|string|max:1000',
            'source_tag' => 'required|string|max:40',
            'is_featured' => 'boolean',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');

        $review->update($data);

        return back()->with('success', 'Review updated.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}
