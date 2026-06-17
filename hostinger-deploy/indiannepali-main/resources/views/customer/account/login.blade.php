@extends('layouts.customer')

@push('styles')
<link rel="stylesheet" href="/css/account.css">
@endpush

@section('content')
<div class="acct-auth-wrap">
    <div class="acct-auth-card cust-card">
        <div class="acct-auth-head">
            <x-logo :size="40" />
            <h1>Sign in</h1>
            <p>View orders, reservations, catering quotes, and payment history.</p>
        </div>

        @if($errors->any())
            <div class="acct-alert acct-alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('account.login') }}" class="acct-form">
            @csrf
            <label class="cust-field"><span>Email</span><input class="cust-inp" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"></label>
            <x-password-input name="password" label="Password" :required="true" autocomplete="current-password" />
            <p style="margin:-6px 0 14px;text-align:right"><a href="{{ route('account.password.forgot') }}" class="acct-forgot">Forgot password?</a></p>
            <label class="acct-remember"><input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}> Remember me</label>
            <button type="submit" class="btn btn-gold" style="width:100%">Sign in</button>
        </form>

        <p class="acct-auth-switch">No account yet? <a href="{{ route('account.register') }}">Create one</a></p>
    </div>
</div>
@endsection
@push('scripts')
<script src="/js/account-auth.js"></script>
@endpush

