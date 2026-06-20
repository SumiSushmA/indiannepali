<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.content.index');
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

        return redirect()->route('admin.content.index')->with('success', 'Review added.');
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

        return redirect()->route('admin.content.index')->with('success', 'Review updated.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return redirect()->route('admin.content.index')->with('success', 'Review deleted.');
    }
}
