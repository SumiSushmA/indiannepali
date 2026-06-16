<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerVerificationCode;
use App\Support\CustomerOtp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountAuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('customer.account.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('account.index'));
    }

    public function showRegisterForm(): View
    {
        return view('customer.account.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:120|unique:customers,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $customer = Customer::create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
        ]);

        Auth::guard('customer')->login($customer);
        $request->session()->regenerate();

        return redirect()
            ->route('account.index')
            ->with('success', 'Welcome! Your account is ready.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function showForgotForm(): View
    {
        return view('customer.account.forgot-password');
    }

    public function sendForgotCode(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => 'required|email']);

        $email = strtolower($data['email']);
        $customer = Customer::query()->where('email', $email)->first();

        if (! $customer) {
            return redirect()
                ->route('account.password.reset', ['email' => $email])
                ->with('success', 'If that email is registered, we sent a 6-digit reset code.');
        }

        try {
            CustomerOtp::send($email, CustomerVerificationCode::PURPOSE_PASSWORD_RESET);
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors(['email' => 'We could not send the email. Check your mail settings or try again in a moment.']);
        }

        return redirect()
            ->route('account.password.reset', ['email' => $email])
            ->with('success', 'We sent a 6-digit reset code to '.$email.'. Check your inbox and spam folder.');
    }

    public function showResetForm(Request $request): View|RedirectResponse
    {
        $email = strtolower(trim((string) $request->query('email', '')));

        if ($email === '') {
            return redirect()->route('account.password.forgot');
        }

        return view('customer.account.reset-password', ['email' => $email]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = strtolower($data['email']);

        if (! CustomerOtp::verify($email, $data['code'], CustomerVerificationCode::PURPOSE_PASSWORD_RESET)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['code' => 'That code is invalid or expired. Request a new one.']);
        }

        $customer = Customer::query()->where('email', $email)->firstOrFail();
        $customer->update(['password' => $data['password']]);

        Auth::guard('customer')->login($customer);
        $request->session()->regenerate();

        return redirect()
            ->route('account.index')
            ->with('success', 'Password updated. You are signed in.');
    }
}
