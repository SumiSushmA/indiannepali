<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutStat;
use App\Models\AboutStory;
use App\Models\AboutValue;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Services\AdminData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        return view('admin.about.index', [
            'active' => 'about',
            'badges' => AdminData::getNavBadges(),
            'heroImage' => Setting::get('about_hero_image_path'),
            'story' => AboutStory::query()->orderBy('sort_order')->get(),
            'stats' => AboutStat::query()->orderBy('sort_order')->get(),
            'values' => AboutValue::query()->orderBy('sort_order')->get(),
            'team' => TeamMember::query()->orderBy('sort_order')->get(),
        ]);
    }

    public function updateHero(Request $request): RedirectResponse
    {
        $request->validate([
            'image' => 'required|image|max:4096',
        ]);

        Setting::set('about_hero_image_path', $request->file('image')->store('about', 'public'));

        return back()->with('success', 'Story hero photo updated.');
    }

    public function storeStory(Request $request): RedirectResponse
    {
        $request->validate(['paragraph' => 'required|string|max:2000']);

        AboutStory::create([
            'paragraph' => $request->input('paragraph'),
            'sort_order' => AboutStory::count(),
        ]);

        return back()->with('success', 'Story paragraph added.');
    }

    public function updateStory(Request $request, AboutStory $story): RedirectResponse
    {
        $request->validate(['paragraph' => 'required|string|max:2000']);

        $story->update(['paragraph' => $request->input('paragraph')]);

        return back()->with('success', 'Story paragraph updated.');
    }

    public function destroyStory(AboutStory $story): RedirectResponse
    {
        $story->delete();

        return back()->with('success', 'Story paragraph removed.');
    }

    public function storeStat(Request $request): RedirectResponse
    {
        $request->validate([
            'value' => 'required|string|max:60',
            'label' => 'required|string|max:120',
        ]);

        AboutStat::create([
            'value' => $request->input('value'),
            'label' => $request->input('label'),
            'sort_order' => AboutStat::count(),
        ]);

        return back()->with('success', 'Stat added.');
    }

    public function updateStat(Request $request, AboutStat $stat): RedirectResponse
    {
        $request->validate([
            'value' => 'required|string|max:60',
            'label' => 'required|string|max:120',
        ]);

        $stat->update($request->only('value', 'label'));

        return back()->with('success', 'Stat updated.');
    }

    public function destroyStat(AboutStat $stat): RedirectResponse
    {
        $stat->delete();

        return back()->with('success', 'Stat removed.');
    }

    public function storeValue(Request $request): RedirectResponse
    {
        $request->validate([
            'icon' => 'required|string|max:30',
            'title' => 'required|string|max:120',
            'body' => 'required|string|max:500',
        ]);

        AboutValue::create([
            'icon' => $request->input('icon'),
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'sort_order' => AboutValue::count(),
        ]);

        return back()->with('success', 'Value added.');
    }

    public function updateValue(Request $request, AboutValue $value): RedirectResponse
    {
        $request->validate([
            'icon' => 'required|string|max:30',
            'title' => 'required|string|max:120',
            'body' => 'required|string|max:500',
        ]);

        $value->update($request->only('icon', 'title', 'body'));

        return back()->with('success', 'Value updated.');
    }

    public function destroyValue(AboutValue $value): RedirectResponse
    {
        $value->delete();

        return back()->with('success', 'Value removed.');
    }

    public function storeTeam(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:120',
            'role' => 'required|string|max:120',
            'tag' => 'nullable|string|max:60',
            'image' => 'nullable|image|max:4096',
        ]);

        $data = [
            'name' => $request->input('name'),
            'role' => $request->input('role'),
            'tag' => $request->input('tag'),
            'is_published' => $request->boolean('is_published', true),
            'sort_order' => TeamMember::count(),
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('about/team', 'public');
        }

        TeamMember::create($data);

        return back()->with('success', 'Team member added.');
    }

    public function updateTeam(Request $request, TeamMember $member): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:120',
            'role' => 'required|string|max:120',
            'tag' => 'nullable|string|max:60',
            'image' => 'nullable|image|max:4096',
        ]);

        $data = [
            'name' => $request->input('name'),
            'role' => $request->input('role'),
            'tag' => $request->input('tag'),
            'is_published' => $request->boolean('is_published'),
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('about/team', 'public');
        }

        $member->update($data);

        return back()->with('success', 'Team member updated.');
    }

    public function destroyTeam(TeamMember $member): RedirectResponse
    {
        $member->delete();

        return back()->with('success', 'Team member removed.');
    }
}
