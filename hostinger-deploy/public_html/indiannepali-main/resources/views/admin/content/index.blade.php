@extends('layouts.admin')

@php
$typeTone = ['Text' => 'neutral', 'Promotion' => 'gold', 'Media' => 'purple'];
@endphp

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px;">
    <div>
        <h1 style="font-size:30px;font-weight:600;">Website content</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">Edit the live customer site copy, promotions and media.</p>
    </div>
    <a href="{{ route('home') }}" target="_blank" class="btn btn-ghost btn-sm" style="text-decoration:none;">Preview site ↗</a>
</div>

<div class="adm-card" style="padding:8px;">
    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead>
                <tr>
                    <th>Section</th><th>Current value</th><th>Type</th><th>Updated</th>
                </tr>
            </thead>
            <tbody>
                @foreach($content as $c)
                <tr>
                    <td><span style="font-weight:600;color:var(--cream);">{{ $c['section'] }}</span></td>
                    <td>
                        <form action="{{ route('admin.content.update', $c['section']) }}" method="POST" style="display:flex;gap:8px;align-items:center;">
                            @csrf @method('PATCH')
                            <input name="value" value="{{ $c['value'] }}" style="flex:1;min-width:0;background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:8px 12px;color:var(--cream);font-size:14px;font-family:var(--sans);">
                            <button type="submit" class="btn btn-ghost btn-sm">Save</button>
                        </form>
                    </td>
                    <td>@include('admin.partials.badge', ['tone' => $typeTone[$c['type']] ?? 'neutral', 'label' => $c['type']])</td>
                    <td><span style="font-size:13px;color:var(--muted);">{{ $c['updated'] }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
