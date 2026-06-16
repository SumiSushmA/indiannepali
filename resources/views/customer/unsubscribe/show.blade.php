@extends('layouts.customer')

@push('styles')
<link rel="stylesheet" href="/css/unsubscribe.css">
@endpush

@section('content')
<div class="cust-unsub-wrap" style="padding-top:120px">
    <div class="cust-card cust-unsub-card" style="text-align:center">
        @if(session('unsubscribed') || $already)
            <div class="cust-unsub-icon cust-unsub-icon--ok">
                <x-icon name="check" :size="36" />
            </div>
            <h1 style="font-size:36px;margin-bottom:12px">You’re unsubscribed</h1>
            <p style="color:var(--sand);font-size:16px;line-height:1.65;max-width:420px;margin:0 auto 24px">
                <strong style="color:var(--cream)">{{ $preference->email }}</strong> will no longer receive marketing emails from us.
            </p>
            <a href="{{ route('home') }}" class="btn btn-gold">Back to home</a>
        @else
            <h1 style="font-size:36px;margin-bottom:12px">Unsubscribe this email?</h1>
            <p style="color:var(--sand);font-size:16px;line-height:1.65;margin-bottom:8px">
                <strong style="color:var(--cream)">{{ $preference->email }}</strong>
            </p>
            <p style="color:var(--muted);font-size:14px;line-height:1.6;margin-bottom:28px">
                You will stop receiving marketing emails, offers, and newsletters. Order confirmations and security emails may still be sent.
            </p>

            <form action="{{ route('unsubscribe.store') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $preference->token }}">
                <button type="submit" class="btn btn-gold" style="width:100%;max-width:280px">Unsubscribe</button>
            </form>
        @endif
    </div>
</div>
@endsection
