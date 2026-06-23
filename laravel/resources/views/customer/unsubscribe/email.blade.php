@extends('layouts.unsubscribe-minimal')

@section('content')
<div class="unsub-minimal__card" role="dialog" aria-modal="true" aria-labelledby="unsub-email-title">
    @if(!empty($error))
        <h1 id="unsub-email-title" class="unsub-minimal__title">Link expired</h1>
        <p class="unsub-minimal__text">This unsubscribe link is invalid or has expired.</p>
    @elseif(session('unsubscribed') || ($already ?? false))
        <div class="unsub-minimal__icon unsub-minimal__icon--ok" aria-hidden="true">✓</div>
        <h1 id="unsub-email-title" class="unsub-minimal__title">You’re unsubscribed</h1>
        <p class="unsub-minimal__text">
            <strong>{{ $preference->email }}</strong> will no longer receive marketing emails from us.
            Order confirmations and security emails may still be sent when needed.
        </p>
        <p class="unsub-minimal__hint">You can close this window.</p>
    @else
        <h1 id="unsub-email-title" class="unsub-minimal__title">Unsubscribe from emails?</h1>
        <p class="unsub-minimal__email">{{ $preference->email }}</p>
        <p class="unsub-minimal__text">
            You will stop receiving marketing emails, offers, and newsletters.
            Order confirmations and security emails may still be sent.
        </p>
        <form action="{{ route('unsubscribe.store') }}" method="POST" class="unsub-minimal__actions">
            @csrf
            <input type="hidden" name="token" value="{{ $preference->token }}">
            <input type="hidden" name="return_to" value="email">
            <button type="button" class="btn btn-ghost" onclick="window.close()">Cancel</button>
            <button type="submit" class="btn btn-gold">Yes, unsubscribe</button>
        </form>
    @endif
</div>
@endsection
