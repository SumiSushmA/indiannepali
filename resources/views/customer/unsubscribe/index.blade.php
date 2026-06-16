@extends('layouts.customer')

@push('styles')
<link rel="stylesheet" href="/css/unsubscribe.css">
@endpush

@section('content')
<div class="cust-page-head cust-pad" style="padding-bottom:24px">
    <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">Email preferences</div>
    <h1 style="font-size:clamp(34px,4vw,48px);line-height:1.05">Unsubscribe</h1>
    <p style="color:var(--sand);font-size:16px;line-height:1.65;margin-top:14px;max-width:560px;margin-left:auto;margin-right:auto">
        Stop marketing emails, offers, and newsletters. Order confirmations and security codes may still be sent when needed.
    </p>
</div>

<div class="cust-unsub-wrap">
    <div class="cust-card cust-unsub-card">
        @if($errors->any())
            <div class="cust-unsub-alert cust-unsub-alert--error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('unsubscribe.lookup') }}" method="POST" class="cust-unsub-form">
            @csrf
            <label class="cust-field">
                <span>Your email address</span>
                <input class="cust-inp" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
            </label>
            <button type="submit" class="btn btn-gold" style="width:100%">Unsubscribe</button>
        </form>
    </div>
</div>
@endsection
