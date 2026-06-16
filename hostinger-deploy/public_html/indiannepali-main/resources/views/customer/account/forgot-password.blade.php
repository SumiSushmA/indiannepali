@extends('layouts.customer')

@push('styles')
<link rel="stylesheet" href="/css/account.css">
@endpush

@section('content')
<div class="acct-auth-wrap">
    <div class="acct-auth-card cust-card">
        <div class="acct-auth-head">
            <x-logo :size="40" />
            <h1>Forgot password</h1>
            <p>Enter the <strong style="color:var(--cream)">same email you used to create your account</strong>. We’ll send a 6-digit code there.</p>
            <p style="color:var(--muted);font-size:13.5px;margin-top:10px;line-height:1.55">This is not the restaurant contact email — it must match your sign-in account. Check spam if you don’t see the code within a minute.</p>
        </div>

        @if(session('success'))
            <div class="acct-alert">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="acct-alert acct-alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('account.password.forgot.send') }}" class="acct-form">
            @csrf
            <label class="cust-field"><span>Email</span><input class="cust-inp" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"></label>
            <button type="submit" class="btn btn-gold" style="width:100%">Send reset code</button>
        </form>

        <p class="acct-auth-switch"><a href="{{ route('account.login') }}">Back to sign in</a></p>
    </div>
</div>
@endsection
