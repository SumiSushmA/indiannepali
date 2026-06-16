@extends('layouts.customer')

@push('styles')
<link rel="stylesheet" href="/css/account.css">
@endpush

@section('content')
<div class="acct-auth-wrap">
    <div class="acct-auth-card cust-card">
        <div class="acct-auth-head">
            <x-logo :size="40" />
            <h1>Reset password</h1>
            <p>Enter the 6-digit code sent to <strong style="color:var(--cream)">{{ $email }}</strong> and choose a new password.</p>
        </div>

        @if(session('success'))
            <div class="acct-alert">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="acct-alert acct-alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('account.password.reset.store') }}" class="acct-form">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <label class="cust-field"><span>Reset code</span><input class="cust-inp" type="text" name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" placeholder="6-digit code" required autofocus></label>
            <x-password-input name="password" label="New password" :required="true" autocomplete="new-password" />
            <x-password-input name="password_confirmation" label="Confirm new password" :required="true" autocomplete="new-password" />
            <button type="submit" class="btn btn-gold" style="width:100%">Update password</button>
        </form>

        <p class="acct-auth-switch">
            <a href="{{ route('account.password.forgot') }}">Send a new code</a>
            · <a href="{{ route('account.login') }}">Sign in</a>
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script src="/js/account-auth.js"></script>
@endpush
