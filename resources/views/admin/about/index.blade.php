@extends('layouts.admin')

@section('content')
<div class="adm-page-head">
    <div class="adm-page-head__main">
        <h1 class="adm-page-title">About page</h1>
        <p class="adm-page-sub">Story, stats, values and team shown on the public About page.</p>
    </div>
    <details class="adm-add-menu adm-page-head__action">
        <summary class="btn btn-gold btn-sm"><x-icon name="plus" :size="16"/> Add</summary>
        <div class="adm-add-menu__panel">
            <button type="button" onclick="this.closest('details')?.removeAttribute('open'); document.getElementById('about-add-story-dialog')?.showModal()">Story paragraph</button>
            <button type="button" onclick="this.closest('details')?.removeAttribute('open'); document.getElementById('about-add-stat-dialog')?.showModal()">Stat</button>
            <button type="button" onclick="this.closest('details')?.removeAttribute('open'); document.getElementById('about-add-value-dialog')?.showModal()">Value</button>
            <button type="button" onclick="this.closest('details')?.removeAttribute('open'); document.getElementById('about-add-team-dialog')?.showModal()">Team member</button>
        </div>
    </details>
</div>

<div class="adm-card adm-about-card">
    <h3 style="font-size:17px;font-weight:600;margin-bottom:14px;">Story paragraphs</h3>
    <form action="{{ route('admin.about.hero.update') }}" method="POST" enctype="multipart/form-data" class="adm-about-form" style="margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid var(--line-soft);">
        @csrf @method('PUT')
        <div class="adm-about-hero-upload">
            <div class="adm-about-hero-preview">
                @if($heroImage)
                    <img src="{{ asset('storage/'.$heroImage) }}" alt="Story hero" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <div style="width:100%;height:100%;display:grid;place-items:center;color:var(--muted);font-size:12px;text-align:center;padding:8px;">No photo yet</div>
                @endif
            </div>
            <div class="adm-file-field adm-about-hero-field">
                <span class="adm-file-field__title">Story hero photo</span>
                <span class="adm-file-field__hint">Shown beside story text on the public About page.</span>
                <input name="image" type="file" accept="image/*" required>
                <span data-adm-file-name style="font-size:12px;color:var(--muted);">No file chosen</span>
            </div>
            <button type="submit" class="btn btn-gold btn-sm">Upload photo</button>
        </div>
    </form>
    @foreach($story as $paragraph)
        <form action="{{ route('admin.about.story.update', $paragraph) }}" method="POST" class="adm-about-form adm-about-story-form">
            @csrf @method('PUT')
            <textarea name="paragraph" rows="2">{{ $paragraph->paragraph }}</textarea>
            <div class="adm-about-row-actions">
                <button type="submit" class="btn btn-ghost btn-sm">Save</button>
                <button type="submit" form="delete-story-{{ $paragraph->id }}" style="width:34px;height:34px;border-radius:9px;background:transparent;border:1px solid var(--spice-600);color:var(--spice-400);cursor:pointer;display:grid;place-items:center;" aria-label="Delete paragraph"><x-icon name="trash" :size="16"/></button>
            </div>
        </form>
        <form id="delete-story-{{ $paragraph->id }}" action="{{ route('admin.about.story.destroy', $paragraph) }}" method="POST" data-confirm="Remove this paragraph?">
            @csrf @method('DELETE')
        </form>
    @endforeach
</div>

<div class="adm-card adm-about-card">
    <h3 style="font-size:17px;font-weight:600;margin-bottom:14px;">Stats</h3>
    @foreach($stats as $stat)
        <form action="{{ route('admin.about.stats.update', $stat) }}" method="POST" class="adm-about-form adm-about-stat-row">
            @csrf @method('PUT')
            <input name="value" value="{{ $stat->value }}">
            <input name="label" value="{{ $stat->label }}">
            <div class="adm-about-row-actions">
                <button type="submit" class="btn btn-ghost btn-sm">Save</button>
                <button type="submit" form="delete-stat-{{ $stat->id }}" style="width:34px;height:34px;border-radius:9px;background:transparent;border:1px solid var(--spice-600);color:var(--spice-400);cursor:pointer;display:grid;place-items:center;" aria-label="Delete stat"><x-icon name="trash" :size="16"/></button>
            </div>
        </form>
        <form id="delete-stat-{{ $stat->id }}" action="{{ route('admin.about.stats.destroy', $stat) }}" method="POST" data-confirm="Remove this stat?">
            @csrf @method('DELETE')
        </form>
    @endforeach
</div>

<div class="adm-card adm-about-card">
    <h3 style="font-size:17px;font-weight:600;margin-bottom:14px;">Values</h3>
    @foreach($values as $value)
        <form action="{{ route('admin.about.values.update', $value) }}" method="POST" class="adm-about-form adm-about-value-form">
            @csrf @method('PUT')
            <div class="adm-about-value-row">
                <input name="icon" value="{{ $value->icon }}">
                <input name="title" value="{{ $value->title }}">
                <div class="adm-about-row-actions">
                    <button type="submit" class="btn btn-ghost btn-sm">Save</button>
                    <button type="submit" form="delete-value-{{ $value->id }}" style="width:34px;height:34px;border-radius:9px;background:transparent;border:1px solid var(--spice-600);color:var(--spice-400);cursor:pointer;display:grid;place-items:center;" aria-label="Delete value"><x-icon name="trash" :size="16"/></button>
                </div>
            </div>
            <textarea name="body" rows="2">{{ $value->body }}</textarea>
        </form>
        <form id="delete-value-{{ $value->id }}" action="{{ route('admin.about.values.destroy', $value) }}" method="POST" data-confirm="Remove this value?">
            @csrf @method('DELETE')
        </form>
    @endforeach
</div>

<div class="adm-card adm-about-card" style="margin-bottom:0;">
    <h3 style="font-size:17px;font-weight:600;margin-bottom:14px;">Team</h3>
    @foreach($team as $member)
        <div class="adm-about-team-block">
            <form action="{{ route('admin.about.team.update', $member) }}" method="POST" enctype="multipart/form-data" class="adm-about-form adm-about-team-form">
                @csrf @method('PUT')
                <div class="adm-about-team-row">
                    <div class="adm-about-team-photo">
                        @if($member->image_path)
                            <img src="{{ asset('storage/'.$member->image_path) }}" alt="{{ $member->name }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="width:100%;height:100%;display:grid;place-items:center;color:var(--muted);font-size:10px;text-align:center;padding:4px;">No photo</div>
                        @endif
                    </div>
                    <input name="name" value="{{ $member->name }}">
                    <input name="role" value="{{ $member->role }}">
                    <input name="tag" value="{{ $member->tag }}">
                    <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--cream-2);">
                        <input type="checkbox" name="is_published" value="1" @checked($member->is_published)> Published
                    </label>
                    <div class="adm-about-row-actions">
                        <button type="submit" class="btn btn-ghost btn-sm">Save</button>
                        <button type="submit" form="delete-team-{{ $member->id }}" style="width:34px;height:34px;border-radius:9px;background:transparent;border:1px solid var(--spice-600);color:var(--spice-400);cursor:pointer;display:grid;place-items:center;" aria-label="Delete team member"><x-icon name="trash" :size="16"/></button>
                    </div>
                </div>
                <div class="adm-file-field">
                    <span class="adm-file-field__title">Member photo</span>
                    <span class="adm-file-field__hint">Leave blank to keep the current photo.</span>
                    <input name="image" type="file" accept="image/*">
                    <span data-adm-file-name style="font-size:12px;color:var(--muted);">No file chosen</span>
                </div>
            </form>
            <form id="delete-team-{{ $member->id }}" action="{{ route('admin.about.team.destroy', $member) }}" method="POST" data-confirm="Remove this team member?">
                @csrf @method('DELETE')
            </form>
        </div>
    @endforeach
</div>

@push('modals')
<dialog id="about-add-story-dialog" class="adm-dialog" style="width:min(760px,calc(100vw - 28px));">
    <form action="{{ route('admin.about.story.store') }}" method="POST" style="padding:18px;display:grid;gap:12px;">
        @csrf
        <h4 style="margin:0;font-size:19px;font-weight:600;">Add story paragraph</h4>
        <textarea name="paragraph" placeholder="Add a story paragraph…" required rows="4" class="adm-inp"></textarea>
        <div style="display:flex;justify-content:flex-end;gap:8px;flex-wrap:wrap;">
            <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('about-add-story-dialog')?.close()">Cancel</button>
            <button type="submit" class="btn btn-gold btn-sm">Add</button>
        </div>
    </form>
</dialog>

<dialog id="about-add-stat-dialog" class="adm-dialog" style="width:min(620px,calc(100vw - 28px));">
    <form action="{{ route('admin.about.stats.store') }}" method="POST" style="padding:18px;display:grid;gap:12px;">
        @csrf
        <h4 style="margin:0;font-size:19px;font-weight:600;">Add stat</h4>
        <input name="value" placeholder="Value (e.g. 12+)" required class="adm-inp">
        <input name="label" placeholder="Label" required class="adm-inp">
        <div style="display:flex;justify-content:flex-end;gap:8px;flex-wrap:wrap;">
            <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('about-add-stat-dialog')?.close()">Cancel</button>
            <button type="submit" class="btn btn-gold btn-sm">Add</button>
        </div>
    </form>
</dialog>

<dialog id="about-add-value-dialog" class="adm-dialog" style="width:min(760px,calc(100vw - 28px));">
    <form action="{{ route('admin.about.values.store') }}" method="POST" style="padding:18px;display:grid;gap:12px;">
        @csrf
        <h4 style="margin:0;font-size:19px;font-weight:600;">Add value</h4>
        <div class="adm-about-dialog-grid-2">
            <input name="icon" placeholder="Icon (e.g. heart)" required class="adm-inp">
            <input name="title" placeholder="Title" required class="adm-inp">
        </div>
        <textarea name="body" placeholder="Description" required rows="3" class="adm-inp"></textarea>
        <div style="display:flex;justify-content:flex-end;gap:8px;flex-wrap:wrap;">
            <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('about-add-value-dialog')?.close()">Cancel</button>
            <button type="submit" class="btn btn-gold btn-sm">Add</button>
        </div>
    </form>
</dialog>

<dialog id="about-add-team-dialog" class="adm-dialog" style="width:min(860px,calc(100vw - 28px));">
    <form action="{{ route('admin.about.team.store') }}" method="POST" enctype="multipart/form-data" style="padding:18px;display:grid;gap:12px;">
        @csrf
        <h4 style="margin:0;font-size:19px;font-weight:600;">Add team member</h4>
        <div class="adm-about-dialog-grid-3">
            <input name="name" placeholder="Name" required class="adm-inp">
            <input name="role" placeholder="Role" required class="adm-inp">
            <input name="tag" placeholder="Tag (optional)" class="adm-inp">
        </div>
        <div class="adm-file-field">
            <span class="adm-file-field__title">Member photo</span>
            <span class="adm-file-field__hint">Shown on the team member card on the public About page.</span>
            <input name="image" type="file" accept="image/*">
            <span data-adm-file-name style="font-size:12px;color:var(--muted);">No file chosen</span>
        </div>
        <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--sand);"><input type="checkbox" name="is_published" value="1" checked> Published</label>
        <div style="display:flex;justify-content:flex-end;gap:8px;flex-wrap:wrap;">
            <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('about-add-team-dialog')?.close()">Cancel</button>
            <button type="submit" class="btn btn-gold btn-sm">Add member</button>
        </div>
    </form>
</dialog>
@endpush
@endsection
