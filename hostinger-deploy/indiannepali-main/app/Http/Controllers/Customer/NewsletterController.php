<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Support\EmailPreferences;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|max:120|unique:newsletter_subscribers,email',
        ]);

        $email = strtolower($request->input('email'));

        if (EmailPreferences::isUnsubscribed($email)) {
            return back()->withErrors(['email' => 'This email has unsubscribed from marketing messages. Use the unsubscribe page to manage preferences.']);
        }

        NewsletterSubscriber::create([
            'email' => $email,
            'subscribed_at' => now(),
        ]);

        return back()->with('success', 'Thanks for subscribing!');
    }
}
