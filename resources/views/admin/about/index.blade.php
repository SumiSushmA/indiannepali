@extends('layouts.admin')

@section('content')
<div style="margin-bottom:26px;">
    <h1 style="font-size:30px;font-weight:600;">About page</h1>
    <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">Story, stats, values and team shown on the public About page.</p>
</div>

@if(session('success'))
    <div class="adm-card" style="padding:14px 18px;margin-bottom:16px;border-color:var(--gold-700);color:var(--gold-400)">{{ session('success') }}</div>
@endif

<div class="adm-card" style="padding:22px;margin-bottom:18px;">
    <h3 style="font-size:17px;font-weight:600;margin-bottom:14px;">Story paragraphs</h3>
    <form action="{{ route('admin.about.story.store') }}" method="POST" style="display:flex;gap:12px;margin-bottom:16px;">
        @csrf
        <textarea name="paragraph" placeholder="Add a story paragraph…" required rows="2" style="flex:1;background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);resize:vertical;"></textarea>
        <button type="submit" class="btn btn-gold btn-sm" style="align-self:flex-start;">Add</button>
    </form>
    @foreach($story as $paragraph)
        <form action="{{ route('admin.about.story.update', $paragraph) }}" method="POST" style="display:flex;gap:10px;margin-bottom:10px;">
            @csrf @method('PUT')
            <textarea name="paragraph" rows="2" style="flex:1;background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:10px 12px;color:var(--cream);font-size:14px;font-family:var(--sans);resize:vertical;">{{ $paragraph->paragraph }}</textarea>
            <button type="submit" class="btn btn-ghost btn-sm">Save</button>
        </form>
        <form action="{{ route('admin.about.story.destroy', $paragraph) }}" method="POST" style="margin:-4px 0 14px;" onsubmit="return confirm('Remove this paragraph?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--spice-400);">Delete</button>
        </form>
    @endforeach
</div>

<div class="adm-card" style="padding:22px;margin-bottom:18px;">
    <h3 style="font-size:17px;font-weight:600;margin-bottom:14px;">Stats</h3>
    <form action="{{ route('admin.about.stats.store') }}" method="POST" style="display:grid;grid-template-columns:1fr 2fr auto;gap:10px;margin-bottom:16px;">
        @csrf
        <input name="value" placeholder="Value (e.g. 12+)" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
        <input name="label" placeholder="Label" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
        <button type="submit" class="btn btn-gold btn-sm">Add</button>
    </form>
    @foreach($stats as $stat)
        <form action="{{ route('admin.about.stats.update', $stat) }}" method="POST" style="display:grid;grid-template-columns:1fr 2fr auto;gap:10px;margin-bottom:6px;align-items:center;">
            @csrf @method('PUT')
            <input name="value" value="{{ $stat->value }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:8px 12px;color:var(--cream);font-size:14px;font-family:var(--sans);">
            <input name="label" value="{{ $stat->label }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:8px 12px;color:var(--cream);font-size:14px;font-family:var(--sans);">
            <button type="submit" class="btn btn-ghost btn-sm">Save</button>
        </form>
        <form action="{{ route('admin.about.stats.destroy', $stat) }}" method="POST" style="margin:-4px 0 14px;" onsubmit="return confirm('Remove this stat?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--spice-400);">Delete</button>
        </form>
    @endforeach
</div>

<div class="adm-card" style="padding:22px;margin-bottom:18px;">
    <h3 style="font-size:17px;font-weight:600;margin-bottom:14px;">Values</h3>
    <form action="{{ route('admin.about.values.store') }}" method="POST" style="display:grid;gap:10px;margin-bottom:16px;">
        @csrf
        <div style="display:grid;grid-template-columns:120px 1fr;gap:10px;">
            <input name="icon" placeholder="Icon (e.g. heart)" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
            <input name="title" placeholder="Title" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
        </div>
        <textarea name="body" placeholder="Description" required rows="2" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);resize:vertical;"></textarea>
        <button type="submit" class="btn btn-gold btn-sm" style="justify-self:start;">Add value</button>
    </form>
    @foreach($values as $value)
        <form action="{{ route('admin.about.values.update', $value) }}" method="POST" style="display:grid;gap:8px;margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid var(--line-soft);">
            @csrf @method('PUT')
            <div style="display:grid;grid-template-columns:120px 1fr auto;gap:10px;align-items:center;">
                <input name="icon" value="{{ $value->icon }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:8px 12px;color:var(--cream);font-size:14px;font-family:var(--sans);">
                <input name="title" value="{{ $value->title }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:8px 12px;color:var(--cream);font-size:14px;font-family:var(--sans);">
                <button type="submit" class="btn btn-ghost btn-sm">Save</button>
            </div>
            <textarea name="body" rows="2" style="background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:8px 12px;color:var(--cream);font-size:14px;font-family:var(--sans);resize:vertical;">{{ $value->body }}</textarea>
        </form>
        <form action="{{ route('admin.about.values.destroy', $value) }}" method="POST" style="margin:-8px 0 16px;" onsubmit="return confirm('Remove this value?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--spice-400);">Delete</button>
        </form>
    @endforeach
</div>

<div class="adm-card" style="padding:22px;">
    <h3 style="font-size:17px;font-weight:600;margin-bottom:14px;">Team</h3>
    <form action="{{ route('admin.about.team.store') }}" method="POST" style="display:grid;gap:10px;margin-bottom:16px;">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:10px;align-items:center;">
            <input name="name" placeholder="Name" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
            <input name="role" placeholder="Role" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
            <input name="tag" placeholder="Tag (optional)" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
            <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--cream-2);white-space:nowrap;">
                <input type="checkbox" name="is_published" value="1" checked> Published
            </label>
        </div>
        <button type="submit" class="btn btn-gold btn-sm" style="justify-self:start;">Add member</button>
    </form>
    @foreach($team as $member)
        <form action="{{ route('admin.about.team.update', $member) }}" method="POST" style="display:grid;grid-template-columns:1fr 1fr 1fr auto auto;gap:10px;margin-bottom:10px;align-items:center;">
            @csrf @method('PUT')
            <input name="name" value="{{ $member->name }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:8px 12px;color:var(--cream);font-size:14px;font-family:var(--sans);">
            <input name="role" value="{{ $member->role }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:8px 12px;color:var(--cream);font-size:14px;font-family:var(--sans);">
            <input name="tag" value="{{ $member->tag }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:8px 12px;color:var(--cream);font-size:14px;font-family:var(--sans);">
            <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--cream-2);white-space:nowrap;">
                <input type="checkbox" name="is_published" value="1" @checked($member->is_published)> Published
            </label>
            <button type="submit" class="btn btn-ghost btn-sm">Save</button>
        </form>
        <form action="{{ route('admin.about.team.destroy', $member) }}" method="POST" style="margin:-4px 0 14px;" onsubmit="return confirm('Remove this team member?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--spice-400);">Delete</button>
        </form>
    @endforeach
</div>
@endsection
