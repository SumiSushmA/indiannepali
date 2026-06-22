@extends('layouts.customer')

@php
$pageTitle = 'My Account';
$tabs = [
    'orders' => ['label' => 'Orders', 'icon' => 'bag'],
    'reservations' => ['label' => 'Reservations', 'icon' => 'cal'],
    'catering' => ['label' => 'Catering', 'icon' => 'users'],
    'messages' => ['label' => 'Messages', 'icon' => 'mail'],
    'payments' => ['label' => 'Payments', 'icon' => 'dollar'],
    'reviews' => ['label' => 'Reviews', 'icon' => 'star'],
    'profile' => ['label' => 'Profile', 'icon' => 'user'],
    'password' => ['label' => 'Password', 'icon' => 'settings'],
];
@endphp

@push('styles')
<link rel="stylesheet" href="/css/account.css?v={{ filemtime(public_path('css/account.css')) }}">
@endpush

@section('content')
<div class="cust-page-head cust-pad acct-page-head">
    <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">My account</div>
    <h1 style="font-size:clamp(34px,4.5vw,54px);line-height:1.05">Welcome back, {{ $customer->name }}</h1>
    <p style="color:var(--sand);margin-top:14px">Your orders, reservations, catering quotes, contact replies, and payment history — all in one place.</p>
</div>

<div class="acct-shell">
    <aside class="acct-sidebar cust-card">
        <div class="acct-user">
            <div class="acct-avatar">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
            <div>
                <strong>{{ $customer->name }}</strong>
                <span>{{ $customer->email }}</span>
            </div>
        </div>

        <nav class="acct-tabs">
            @foreach($tabs as $key => $meta)
                <a href="{{ route('account.index', ['tab' => $key]) }}" class="acct-tab {{ $tab === $key ? 'active' : '' }}">
                    <x-icon :name="$meta['icon']" :size="18" />
                    {{ $meta['label'] }}
                </a>
            @endforeach
        </nav>

        <form action="{{ route('account.logout') }}" method="POST" class="acct-logout">
            @csrf
            <button type="submit" class="btn btn-ghost btn-sm" style="width:100%">Sign out</button>
        </form>
    </aside>

    <div class="acct-main">
        @if($tab === 'orders')
            <div class="acct-panel cust-card">
                <div class="acct-panel-head">
                    <h2>Online orders</h2>
                </div>

                @if($orders->isEmpty())
                    <div class="acct-empty">
                        <x-icon name="bag" :size="36" color="var(--muted)" />
                        <p>No orders yet.</p>
                        <a href="{{ route('menu') }}" class="btn btn-ghost btn-sm">Browse menu</a>
                    </div>
                @else
                    <div class="acct-list">
                        @foreach($orders as $order)
                            <a href="{{ route('account.orders.show', $order->order_number) }}" class="acct-row">
                                <div>
                                    <strong>{{ $order->order_number }}</strong>
                                    <span>{{ ucfirst($order->fulfillment_type) }} · {{ ($order->placed_at ?? $order->created_at)->format('M j, Y g:i A') }}</span>
                                </div>
                                <div class="acct-row-meta">
                                    <span class="acct-badge acct-badge-{{ strtolower($order->status) }}">{{ $order->status }}</span>
                                    <strong>${{ number_format($order->total, 2) }}</strong>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @elseif($tab === 'reservations')
            <div class="acct-panel cust-card">
                <div class="acct-panel-head">
                    <h2>Table reservations</h2>
                </div>

                @if($reservations->isEmpty())
                    <div class="acct-empty">
                        <x-icon name="cal" :size="36" color="var(--muted)" />
                        <p>No reservations yet.</p>
                        <a href="{{ route('reserve') }}" class="btn btn-ghost btn-sm">Reserve now</a>
                    </div>
                @else
                    <div class="acct-list">
                        @foreach($reservations as $reservation)
                            <div class="acct-row static">
                                <div>
                                    <strong>{{ $reservation->reference }}</strong>
                                    <span>{{ $reservation->reserved_date->format('l, F j, Y') }} at {{ $reservation->reserved_time }} · {{ $reservation->party_size }} guests</span>
                                    @if($reservation->occasion && $reservation->occasion !== '—')
                                        <span class="acct-sub">{{ $reservation->occasion }}</span>
                                    @endif
                                </div>
                                <div class="acct-row-meta">
                                    <span class="acct-badge acct-badge-{{ strtolower($reservation->status) }}">{{ $reservation->status }}</span>
                                    @if($reservation->table_number)
                                        <span class="acct-muted">Table {{ $reservation->table_number }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @elseif($tab === 'catering')
            <div class="acct-panel cust-card">
                <div class="acct-panel-head">
                    <h2>Catering inquiries</h2>
                </div>

                @if($cateringInquiries->isEmpty())
                    <div class="acct-empty">
                        <x-icon name="users" :size="36" color="var(--muted)" />
                        <p>No catering inquiries yet.</p>
                        <a href="{{ route('catering') }}" class="btn btn-ghost btn-sm">Plan an event</a>
                    </div>
                @else
                    <div class="acct-list">
                        @foreach($cateringInquiries as $inquiry)
                            <div class="acct-row static">
                                <div>
                                    <strong>{{ $inquiry->reference }} · {{ $inquiry->event_type }}</strong>
                                    <span>{{ $inquiry->event_date->format('M j, Y') }} · {{ $inquiry->guest_count }} guests</span>
                                    @if($inquiry->package)
                                        <span class="acct-sub">{{ $inquiry->package->name }}</span>
                                    @endif
                                </div>
                                <div class="acct-row-meta">
                                    <span class="acct-badge acct-badge-{{ strtolower($inquiry->status) }}">{{ $inquiry->status }}</span>
                                    @if($inquiry->quoted_value)
                                        <strong>${{ number_format($inquiry->quoted_value, 0) }} est.</strong>
                                    @else
                                        <span class="acct-muted">Quote pending</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @elseif($tab === 'messages')
            <div class="acct-panel cust-card">
                <div class="acct-panel-head">
                    <h2>Contact messages</h2>
                </div>

                @if($contactMessages->isEmpty())
                    <div class="acct-empty">
                        <x-icon name="mail" :size="36" color="var(--muted)" />
                        <p>No messages yet.</p>
                        <a href="{{ route('contact') }}" class="btn btn-ghost btn-sm">Contact us</a>
                    </div>
                @else
                    <div class="acct-list acct-message-list">
                        @foreach($contactMessages as $msg)
                            <div class="acct-row static acct-message">
                                <div>
                                    <strong>{{ $msg->reference }} · {{ $msg->subject }}</strong>
                                    <span>{{ $msg->created_at->format('M j, Y') }}</span>
                                    <p class="acct-message-body">{{ $msg->message }}</p>
                                    @if($msg->admin_reply)
                                        <div class="acct-reply">
                                            <span class="acct-reply-label">Our reply · {{ $msg->replied_at?->format('M j, Y g:i A') }}</span>
                                            <p>{{ $msg->admin_reply }}</p>
                                        </div>
                                    @else
                                        <span class="acct-muted">We're reviewing your message and will reply soon.</span>
                                    @endif
                                </div>
                                <div class="acct-row-meta">
                                    <span class="acct-badge acct-badge-{{ strtolower($msg->status) }}">{{ $msg->status }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @elseif($tab === 'payments')
            <div class="acct-panel cust-card">
                <div class="acct-panel-head">
                    <h2>Payment history</h2>
                </div>

                @if($payments->isEmpty())
                    <div class="acct-empty">
                        <x-icon name="dollar" :size="36" color="var(--muted)" />
                        <p>No payments on file yet.</p>
                    </div>
                @else
                    <div class="acct-list">
                        @foreach($payments as $payment)
                            <div class="acct-row static">
                                <div>
                                    <strong>{{ $payment->order_number }}</strong>
                                    <span>{{ ($payment->placed_at ?? $payment->created_at)->format('M j, Y') }}</span>
                                    @if($payment->payment_reference)
                                        <span class="acct-sub">Ref {{ $payment->payment_reference }}</span>
                                    @endif
                                </div>
                                <div class="acct-row-meta">
                                    <span class="acct-badge acct-badge-{{ strtolower($payment->payment_status ?? 'paid') }}">{{ ucfirst($payment->payment_status ?? 'Paid') }}</span>
                                    <div style="text-align:right">
                                        <strong>${{ number_format($payment->total, 2) }}</strong>
                                        @if($payment->card_last4)
                                            <span class="acct-muted">{{ ucfirst($payment->card_brand ?? 'Card') }} ···· {{ $payment->card_last4 }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @elseif($tab === 'reviews')
            <div class="acct-panel cust-card">
                <div class="acct-panel-head">
                    <h2>Your reviews</h2>
                </div>

                <form action="{{ route('account.reviews.store') }}" method="POST" class="acct-form" style="max-width:640px;margin-bottom:18px;">
                    @csrf
                    <label class="cust-field">
                        <span>Stars</span>
                        <input class="cust-inp" type="number" name="stars" min="1" max="5" value="{{ old('stars', 5) }}" required>
                    </label>
                    <label class="cust-field">
                        <span>Your review</span>
                        <textarea class="cust-inp" name="body" rows="4" maxlength="1000" placeholder="Share your experience..." required>{{ old('body') }}</textarea>
                    </label>
                    <button type="submit" class="btn btn-gold">Post review</button>
                </form>

                @if($reviews->isEmpty())
                    <div class="acct-empty">
                        <x-icon name="star" :size="36" color="var(--muted)" />
                        <p>No reviews posted yet.</p>
                    </div>
                @else
                    <div class="acct-list">
                        @foreach($reviews as $review)
                            <div class="acct-row static">
                                <div>
                                    <strong>{{ $review->author_name }}</strong>
                                    <span>{{ $review->created_at->format('M j, Y') }} · {{ $review->stars }}/5 stars</span>
                                    <p class="acct-message-body">{{ $review->body }}</p>
                                </div>
                                <div class="acct-row-meta">
                                    <span class="acct-badge {{ $review->is_featured ? 'acct-badge-confirmed' : 'acct-badge-pending' }}">{{ $review->is_featured ? 'Live' : 'Hidden' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @elseif($tab === 'profile')
            <div class="acct-panel cust-card">
                <div class="acct-panel-head">
                    <h2>Profile settings</h2>
                </div>

                <form action="{{ route('account.profile.update') }}" method="POST" class="acct-form" style="max-width:520px">
                    @csrf
                    @method('PATCH')
                    <label class="cust-field"><span>Full name</span><input class="cust-inp" name="name" value="{{ old('name', $customer->name) }}" required></label>
                    <label class="cust-field"><span>Email</span><input class="cust-inp" type="email" value="{{ $customer->email }}" disabled><span class="acct-hint">Email cannot be changed here.</span></label>
                    <label class="cust-field"><span>Phone</span><input class="cust-inp" type="tel" name="phone" value="{{ old('phone', $customer->phone) }}"></label>
                    <button type="submit" class="btn btn-gold">Save changes</button>
                </form>
            </div>
        @else
            <div class="acct-panel cust-card">
                <div class="acct-panel-head">
                    <h2>Change password</h2>
                </div>

                @if($errors->any() && ($errors->has('current_password') || $errors->has('password')))
                    <div class="acct-alert acct-alert-error">{{ $errors->first() }}</div>
                @endif

                <form action="{{ route('account.password.change') }}" method="POST" class="acct-form" style="max-width:520px">
                    @csrf
                    @method('PATCH')
                    <x-password-input name="current_password" label="Current password" :required="true" autocomplete="current-password" />
                    <x-password-input name="password" label="New password" :required="true" autocomplete="new-password" hint="At least 8 characters" />
                    <x-password-input name="password_confirmation" label="Confirm new password" :required="true" autocomplete="new-password" />
                    <button type="submit" class="btn btn-gold">Update password</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="/js/account-auth.js"></script>
@endpush
