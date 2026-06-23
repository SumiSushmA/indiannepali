@extends('layouts.admin')

@php
$statusTone = ['Unread' => 'red', 'Open' => 'gold', 'Resolved' => 'neutral'];
$unread = count(array_filter($contact, fn($m) => $m['status'] === 'Unread'));
$open = count(array_filter($contact, fn($m) => $m['status'] === 'Open'));
@endphp

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px;">
    <div>
        <h1 style="font-size:30px;font-weight:600;">Contact inbox</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">{{ $unread }} unread · {{ $open }} open</p>
    </div>
</div>

@if($selected)
<div class="adm-inbox">
    <div class="adm-card" style="display:flex;flex-direction:column;overflow:hidden;padding:0;">
        <div style="display:flex;gap:4px;padding:10px;border-bottom:1px solid var(--line);">
            @foreach(['All', 'Unread', 'Open', 'Resolved'] as $tab)
            <a href="{{ route('admin.inquiries.show', ['inquiry' => $selected['id'], 'filter' => $tab]) }}" style="flex:1;text-align:center;background:{{ ($filter ?? 'All') === $tab ? 'var(--ink-600)' : 'transparent' }};border:none;border-radius:8px;padding:8px 0;cursor:pointer;color:{{ ($filter ?? 'All') === $tab ? 'var(--cream)' : 'var(--muted)' }};font-size:13px;font-weight:600;font-family:var(--sans);text-decoration:none;">{{ $tab }}</a>
            @endforeach
        </div>
        <div style="flex:1;overflow-y:auto;">
            @foreach($contact as $m)
            <a href="{{ route('admin.inquiries.show', ['inquiry' => $m['id'], 'filter' => $filter ?? 'All']) }}" style="display:block;width:100%;text-align:left;border:none;border-bottom:1px solid var(--line-soft);border-left:3px solid {{ $selected['id'] === $m['id'] ? 'var(--gold-500)' : 'transparent' }};background:{{ $selected['id'] === $m['id'] ? 'var(--ink-750)' : 'transparent' }};padding:14px 16px;cursor:pointer;color:inherit;font-family:var(--sans);text-decoration:none;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                    <span style="font-weight:{{ $m['status'] === 'Unread' ? '700' : '600' }};font-size:14px;color:var(--cream);">{{ $m['name'] }}</span>
                    <span style="font-size:11.5px;color:var(--faint);">{{ $m['days'] === 0 ? 'today' : $m['days'] . 'd' }}</span>
                </div>
                <div style="display:flex;gap:8px;align-items:center;margin-bottom:4px;">
                    @if($m['status'] === 'Unread')<span style="width:7px;height:7px;border-radius:99px;background:var(--spice-500);"></span>@endif
                    <span style="font-size:13px;font-weight:600;color:var(--sand);">{{ $m['subject'] }}</span>
                </div>
                <div style="font-size:12.5px;color:var(--muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $m['preview'] }}</div>
            </a>
            @endforeach
        </div>
    </div>
    <div class="adm-card" style="display:flex;flex-direction:column;padding:0;">
        <div style="padding:20px 26px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center;">
            <div>
                <h3 style="font-size:21px;font-weight:600;">{{ $selected['subject'] }}</h3>
                <div style="font-size:13.5px;color:var(--muted);margin-top:3px;">{{ $selected['name'] }} · {{ $selected['email'] }}</div>
            </div>
            @include('admin.partials.badge', ['tone' => $statusTone[$selected['status']] ?? 'neutral', 'dot' => true, 'label' => $selected['status']])
        </div>
        <div style="flex:1;overflow-y:auto;padding:26px;">
            <div style="background:var(--ink-800);border:1px solid var(--line);border-radius:12px;padding:18px;font-size:15px;line-height:1.7;color:var(--cream-2);white-space:pre-wrap;">{{ $selected['message'] ?? $selected['preview'] }}</div>
            @if(!empty($selected['admin_reply']))
            <div style="margin-top:18px;">
                <div style="font-size:12px;font-weight:600;color:var(--gold-400);text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Your reply · {{ $selected['replied_at'] ?? '' }}</div>
                <div style="background:var(--ink-750);border:1px solid var(--gold-700);border-radius:12px;padding:18px;font-size:15px;line-height:1.7;color:var(--cream-2);white-space:pre-wrap;">{{ $selected['admin_reply'] }}</div>
            </div>
            @endif
        </div>
        <div style="padding:18px;border-top:1px solid var(--line);background:var(--ink-800);">
            <form action="{{ route('admin.inquiries.reply', $selected['id']) }}" method="POST">
                @csrf
                <textarea name="reply" placeholder="{{ !empty($selected['admin_reply']) ? 'Update reply…' : 'Write a reply…' }}" required style="width:100%;background:var(--ink-750);border:1px solid var(--line);border-radius:10px;padding:13px;color:var(--cream);font-size:14px;font-family:var(--sans);min-height:70px;resize:none;outline:none;">{{ old('reply', $selected['admin_reply'] ?? '') }}</textarea>
                <div style="display:flex;gap:10px;margin-top:12px;">
                    <button type="submit" class="btn btn-gold btn-sm" style="margin-left:auto;"><x-icon name="mail" :size="15"/> Send reply</button>
                </div>
            </form>
            <form action="{{ route('admin.inquiries.status', $selected['id']) }}" method="POST" style="margin-top:10px;">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="Resolved">
                <button type="submit" class="btn btn-ghost btn-sm">Mark resolved</button>
            </form>
        </div>
    </div>
</div>
@else
<div class="adm-card" style="padding:40px;text-align:center;color:var(--muted);">No inquiries yet.</div>
@endif
@endsection
