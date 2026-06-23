@php
$notifications = $adminNotifications ?? [];
$notificationCount = $adminNotificationCount ?? 0;
@endphp
<header class="adm-shell-bar adm-topbar">
    <button id="adm-burger" class="adm-burger" type="button" aria-label="Open menu">
        <x-icon name="menu" :size="20"/>
    </button>
    <form action="{{ route('admin.orders.index') }}" method="GET" class="adm-topbar__search">
        <x-icon name="search" :size="17" color="var(--muted)"/>
        <input name="q" value="{{ request('q') }}" placeholder="Search orders, guests, dishes…" class="adm-topbar__search-input">
    </form>
    <div class="adm-topbar__actions">
        <a href="/" target="_blank" rel="noopener noreferrer" class="btn btn-ghost btn-sm adm-topbar__view" aria-label="View site">
            <x-icon name="eye" :size="16"/>
            <span class="adm-topbar__view-label">View site</span>
        </a>
        <div class="adm-notif" id="adm-notif">
            <button type="button" class="adm-notif__btn" id="adm-notif-btn" aria-label="Notifications" aria-expanded="false" aria-haspopup="true">
                <x-icon name="bell" :size="19"/>
                @if($notificationCount > 0)
                    <span class="adm-notif__badge">{{ $notificationCount > 9 ? '9+' : $notificationCount }}</span>
                @endif
            </button>
            <div class="adm-notif__panel" id="adm-notif-panel" hidden>
                <div class="adm-notif__head">
                    <div class="adm-notif__head-text">
                        <div class="adm-notif__title">Notifications</div>
                        <div class="adm-notif__sub">{{ $notificationCount }} need attention</div>
                    </div>
                    @if($notificationCount > 0)
                        <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="adm-notif__mark-form">
                            @csrf
                            <button type="submit" class="adm-notif__mark-all">Mark all read</button>
                        </form>
                    @endif
                </div>
                <div class="adm-notif__list">
                    @forelse($notifications as $note)
                    <a href="{{ $note['url'] }}" class="adm-notif__item">
                        <div class="adm-notif__icon adm-notif__icon--{{ $note['tone'] }}">
                            <x-icon :name="$note['icon']" :size="17"/>
                        </div>
                        <div class="adm-notif__body">
                            <div class="adm-notif__item-title">{{ $note['title'] }}</div>
                            <div class="adm-notif__item-msg">{{ $note['message'] }}</div>
                        </div>
                        <div class="adm-notif__time">{{ $note['time'] }}</div>
                    </a>
                    @empty
                    <div class="adm-notif__empty">You're all caught up — no new orders, reservations, or messages.</div>
                    @endforelse
                </div>
                @if($notificationCount > 0)
                <div class="adm-notif__foot">
                    <a href="{{ route('admin.dashboard') }}">View dashboard</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</header>
