<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\EmailPreference;
use App\Services\SiteSettings;
use App\Support\EmailPreferences;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class UnsubscribeController extends Controller
{
    public function index(): View
    {
        return view('customer.unsubscribe.index');
    }

    public function show(string $token): View|RedirectResponse
    {
        if (request('from') === 'email') {
            return redirect()->route('unsubscribe.email', ['token' => $token]);
        }

        $preference = EmailPreference::findByToken($token);

        if (! $preference) {
            return redirect()->route('unsubscribe')->withErrors(['email' => 'This unsubscribe link is invalid or expired.']);
        }

        return view('customer.unsubscribe.show', [
            'preference' => $preference,
            'already' => $preference->isUnsubscribed(),
        ]);
    }

    public function email(string $token): View
    {
        $site = SiteSettings::all();
        $preference = EmailPreference::findByToken($token);

        if (! $preference) {
            return view('customer.unsubscribe.email', [
                'error' => true,
                'site' => $site,
            ]);
        }

        return view('customer.unsubscribe.email', [
            'preference' => $preference,
            'already' => $preference->isUnsubscribed(),
            'site' => $site,
        ]);
    }

    public function oneClick(Request $request, string $token): Response
    {
        $preference = EmailPreference::findByToken($token);

        if (! $preference) {
            return response('Not found', 404);
        }

        if (! $preference->isUnsubscribed()) {
            EmailPreferences::unsubscribe($preference->email);
        }

        return response('Unsubscribed', 200);
    }

    public function lookup(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => 'required|email|max:120']);

        $preference = EmailPreferences::forEmail($data['email']);

        if (! $preference->isUnsubscribed()) {
            EmailPreferences::unsubscribe($preference->email);
        }

        return redirect()
            ->route('unsubscribe.show', ['token' => $preference->token])
            ->with('unsubscribed', true);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'token' => 'required|string|max:64',
            'return_to' => 'nullable|in:email',
        ]);

        $preference = EmailPreference::findByToken($data['token']);

        if (! $preference) {
            return redirect()->route('unsubscribe')->withErrors(['email' => 'This unsubscribe link is invalid.']);
        }

        if (! $preference->isUnsubscribed()) {
            EmailPreferences::unsubscribe($preference->email);
        }

        $route = ($data['return_to'] ?? null) === 'email'
            ? 'unsubscribe.email'
            : 'unsubscribe.show';

        return redirect()
            ->route($route, ['token' => $preference->token])
            ->with('unsubscribed', true);
    }
}
