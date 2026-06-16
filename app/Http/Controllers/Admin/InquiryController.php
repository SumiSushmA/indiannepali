<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactReplyMail;
use App\Models\ContactMessage;
use App\Services\AdminData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

    public function show(Request $request, ContactMessage $inquiry): View
    {
        $filter = $request->query('filter', 'All');
        $messages = AdminData::getContact();

        if ($filter !== 'All') {
            $messages = array_values(array_filter($messages, fn ($m) => $m['status'] === $filter));
        }

        $selected = $inquiry->toLegacy();
        $selected['message'] = $inquiry->message;
        $selected['admin_reply'] = $inquiry->admin_reply;
        $selected['replied_at'] = $inquiry->replied_at?->format('M j, Y g:i A');

        return view('admin.inquiries.index', [
            'active' => 'contact',
            'contact' => $messages,
            'selected' => $selected,
            'filter' => $filter,
            'badges' => AdminData::getNavBadges(),
        ]);
    }

    public function reply(Request $request, ContactMessage $inquiry): RedirectResponse
    {
        $request->validate([
            'reply' => 'required|string|max:2000',
            'status' => 'nullable|in:Open,Resolved',
        ]);

        $reply = $request->input('reply');

        $inquiry->update([
            'admin_reply' => $reply,
            'replied_at' => now(),
            'status' => $request->input('status', 'Resolved'),
            'read_at' => now(),
        ]);

        try {
            Mail::to($inquiry->customer_email)->send(new ContactReplyMail($inquiry));
        } catch (\Throwable) {
            // Mail may be unconfigured locally; reply is still saved for My Account.
        }

        return back()->with('success', 'Reply saved and sent to '.$inquiry->customer_email.'.');
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
