<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('customer.contact.create', [
            'submitted' => session('contact_submitted', false),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:120',
            'subject' => 'nullable|string|max:120',
            'message' => 'required|string|max:2000',
        ]);

        ContactMessage::create([
            'reference' => 'M-'.(880 + ContactMessage::count()),
            'customer_name' => $request->input('name'),
            'customer_email' => $request->input('email'),
            'subject' => $request->input('subject') ?: 'General inquiry',
            'message' => $request->input('message'),
            'status' => 'Unread',
        ]);

        return redirect()->route('contact')->with('contact_submitted', true);
    }
}
