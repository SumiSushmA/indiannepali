<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentBlock;
use App\Services\AdminData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function index(): View
    {
        return view('admin.content.index', [
            'active' => 'content',
            'content' => AdminData::getContent(),
            'badges' => AdminData::getNavBadges(),
        ]);
    }

    public function update(Request $request, ContentBlock $content): RedirectResponse
    {
        $request->validate(['value' => 'required|string|max:2000']);

        $content->update(['value' => $request->input('value')]);

        return back()->with('success', $content->section.' updated.');
    }
}
