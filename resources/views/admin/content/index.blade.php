@extends('layouts.admin')

@php
$typeTone = ['Text' => 'neutral', 'Promotion' => 'gold', 'Media' => 'purple'];
@endphp

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px;">
    <div>
        <h1 style="font-size:30px;font-weight:600;">Home page</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">Edit homepage copy and guest reviews shown on the public site.</p>
    </div>
    <a href="{{ route('home') }}" target="_blank" class="btn btn-ghost btn-sm" style="text-decoration:none;">Preview site ↗</a>
</div>

<div class="adm-card" style="padding:8px;margin-bottom:24px;">
    <div style="padding:16px 18px 0;">
        <h3 style="font-size:17px;font-weight:600;margin-bottom:4px;">Homepage copy</h3>
        <p style="color:var(--muted);font-size:13px;margin-bottom:12px;">These fields appear on the customer homepage and footer.</p>
    </div>
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

<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:18px;">
    <div>
        <h2 style="font-size:22px;font-weight:600;">Guest reviews</h2>
        <p style="color:var(--muted);font-size:14px;margin-top:6px;">{{ $reviews->count() }} reviews · featured on the homepage</p>
    </div>
    <button type="button" class="btn btn-gold btn-sm" onclick="document.getElementById('add-review-dialog')?.showModal()"><x-icon name="plus" :size="16"/> Add review</button>
</div>

<dialog id="add-review-dialog" style="width:min(760px,calc(100vw - 28px));border:1px solid var(--line);border-radius:14px;background:var(--ink-700);color:var(--cream);padding:0;box-shadow:var(--shadow-3);">
    <form action="{{ route('admin.reviews.store') }}" method="POST" style="padding:18px;display:grid;gap:12px;">
        @csrf
        <h3 style="font-size:19px;font-weight:600;margin:0;">Add review</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
            <input name="author_name" placeholder="Author name" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
            <input name="stars" type="number" min="1" max="5" value="5" placeholder="Stars" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
            <input name="source_tag" placeholder="Source (e.g. Google)" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
        </div>
        <textarea name="body" placeholder="Review text" required rows="2" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);resize:vertical;"></textarea>
        <div style="display:flex;gap:12px;align-items:center;justify-content:flex-end;">
            <label style="display:flex;align-items:center;gap:8px;font-size:14px;color:var(--cream-2);margin-right:auto;">
                <input type="checkbox" name="is_featured" value="1" checked> Featured on site
            </label>
            <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('add-review-dialog')?.close()">Cancel</button>
            <button type="submit" class="btn btn-gold btn-sm">Add review</button>
        </div>
    </form>
</dialog>

<div class="adm-card" style="padding:8px;">
    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead>
                <tr>
                    <th>Author</th><th>Stars</th><th>Review</th><th>Source</th><th>Featured</th><th class="right"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                <tr>
                    <td colspan="6" style="padding:16px;">
                        <form action="{{ route('admin.reviews.update', $review) }}" method="POST" style="display:grid;gap:10px;">
                            @csrf @method('PUT')
                            <div style="display:grid;grid-template-columns:1fr 80px 120px auto auto;gap:10px;align-items:center;">
                                <input name="author_name" value="{{ $review->author_name }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:8px 12px;color:var(--cream);font-size:14px;font-family:var(--sans);">
                                <input name="stars" type="number" min="1" max="5" value="{{ $review->stars }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:8px 12px;color:var(--cream);font-size:14px;font-family:var(--sans);">
                                <input name="source_tag" value="{{ $review->source_tag }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:8px 12px;color:var(--cream);font-size:14px;font-family:var(--sans);">
                                <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--cream-2);white-space:nowrap;">
                                    <input type="checkbox" name="is_featured" value="1" @checked($review->is_featured)> Featured
                                </label>
                                <button type="submit" class="btn btn-ghost btn-sm">Save</button>
                            </div>
                            <textarea name="body" rows="2" style="background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:8px 12px;color:var(--cream);font-size:14px;font-family:var(--sans);resize:vertical;">{{ $review->body }}</textarea>
                        </form>
                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" style="margin-top:8px;" data-confirm="Delete this review?">
                            @csrf @method('DELETE')
                            <button type="submit" style="width:34px;height:34px;border-radius:9px;background:transparent;border:1px solid var(--spice-600);color:var(--spice-400);cursor:pointer;display:grid;place-items:center;" aria-label="Delete review"><x-icon name="trash" :size="16"/></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
