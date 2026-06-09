@extends('layouts.admin')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px;">
    <div>
        <h1 style="font-size:30px;font-weight:600;">Newsletter subscribers</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">{{ $subscribers->count() }} subscribers</p>
    </div>
</div>

<div class="adm-card" style="padding:8px;">
    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead>
                <tr>
                    <th>Email</th><th>Subscribed</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $sub)
                <tr>
                    <td><span style="font-weight:600;color:var(--cream);">{{ $sub->email }}</span></td>
                    <td><span style="font-size:13.5px;color:var(--muted);">{{ $sub->subscribed_at?->format('M j, Y g:i A') ?? '—' }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" style="text-align:center;padding:40px;color:var(--muted);">No subscribers yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
