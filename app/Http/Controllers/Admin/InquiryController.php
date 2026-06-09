<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Services\AdminData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $first = ContactMessage::orderByDesc('created_at')->first();

        if ($first) {
            return redirect()->route('admin.inquiries.show', $first);
        }

        return view('admin.inquiries.index', [
            'active' => 'contact',
            'contact' => [],
            'selected' => null,
            'badges' => AdminData::getNavBadges(),
        ]);
    }

    public function show(ContactMessage $inquiry): View
    {
        $messages = AdminData::getContact();
        $selected = $inquiry->toLegacy();
        $selected['message'] = $inquiry->message;

        return view('admin.inquiries.index', [
            'active' => 'contact',
            'contact' => $messages,
            'selected' => $selected,
            'badges' => AdminData::getNavBadges(),
        ]);
    }

    public function reply(Request $request, ContactMessage $inquiry): RedirectResponse
    {
        $request->validate([
            'reply' => 'required|string|max:2000',
            'status' => 'nullable|in:Open,Resolved',
        ]);

        $inquiry->update([
            'status' => $request->input('status', 'Resolved'),
            'read_at' => now(),
        ]);

        return back()->with('success', 'Reply sent and message marked '.$inquiry->status.'.');
    }

    public function updateStatus(Request $request, ContactMessage $inquiry): RedirectResponse
    {
        $request->validate(['status' => 'required|in:Unread,Open,Resolved']);

        $inquiry->update([
            'status' => $request->input('status'),
            'read_at' => $request->input('status') !== 'Unread' ? now() : null,
        ]);

        return back()->with('success', 'Message updated.');
    }
}
