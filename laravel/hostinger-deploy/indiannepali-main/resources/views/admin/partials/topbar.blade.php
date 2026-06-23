@php
$notifications = $adminNotifications ?? [];
$notificationCount = $adminNotificationCount ?? 0;
@endphp
<header class="adm-shell-bar adm-topbar">
    <button id="adm-burger" class="adm-burger" style="background:none;border:1px solid var(--line);border-radius:9px;width:40px;height:40px;cursor:pointer;color:var(--cream);">
        <x-icon name="menu" :size="20"/>
    </button>
    <form action="{{ route('admin.orders.index') }}" method="GET" style="display:flex;align-items:center;gap:10px;background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:0 14px;width:320px;max-width:40vw;">
        <x-icon name="search" :size="17" color="var(--muted)"/>
        <input name="q" value="{{ request('q') }}" placeholder="Search orders, guests, dishes…" style="flex:1;background:none;border:none;outline:none;color:var(--cream);font-size:14px;padding:11px 0;font-family:var(--sans);">
    </form>
    <div style="margin-left:auto;display:flex;align-items:center;gap:12px;">
        <a href="/" target="_blank" class="btn btn-ghost btn-sm" style="text-decoration:none;">
            <x-icon name="eye" :size="16"/> View site
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
                    <div>
                        <div class="adm-notif__title">Notifications</div>
                        <div class="adm-notif__sub">{{ $notificationCount }} need attention</div>
                    </div>
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
