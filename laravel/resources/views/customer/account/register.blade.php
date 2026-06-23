@extends('layouts.customer')

@push('styles')
<link rel="stylesheet" href="/css/account.css">
@endpush

@section('content')
<div class="acct-auth-wrap">
    <div class="acct-auth-card cust-card">
        <div class="acct-auth-head">
            <x-logo :size="40" />
            <h1>Create account</h1>
            <p>Track online orders, table reservations, and catering inquiries in one place.</p>
        </div>

        @if($errors->any())
            <div class="acct-alert acct-alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('account.register') }}" class="acct-form">
            @csrf
            <label class="cust-field"><span>Full name</span><input class="cust-inp" name="name" value="{{ old('name') }}" required autocomplete="name"></label>
            <label class="cust-field"><span>Email</span><input class="cust-inp" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"></label>
            <label class="cust-field"><span>Phone</span><input class="cust-inp" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel"></label>
            <x-password-input name="password" label="Password" :required="true" autocomplete="new-password" hint="At least 8 characters" />
            <x-password-input name="password_confirmation" label="Confirm password" :required="true" autocomplete="new-password" />
            <button type="submit" class="btn btn-gold" style="width:100%">Create account</button>
        </form>

        <p class="acct-auth-switch">Already have an account? <a href="{{ route('account.login') }}">Sign in</a></p>
    </div>
</div>
@endsection
@push('scripts')
<script src="/js/account-auth.js"></script>
@endpush

