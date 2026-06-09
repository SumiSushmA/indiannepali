@extends('layouts.admin')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px;">
    <div>
        <h1 style="font-size:30px;font-weight:600;">Reviews</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">{{ $reviews->count() }} reviews · featured on homepage</p>
    </div>
</div>

@if(session('success'))
    <div class="adm-card" style="padding:14px 18px;margin-bottom:16px;border-color:var(--gold-700);color:var(--gold-400)">{{ session('success') }}</div>
@endif

<div class="adm-card" style="padding:22px;margin-bottom:18px;">
    <h3 style="font-size:17px;font-weight:600;margin-bottom:14px;">Add review</h3>
    <form action="{{ route('admin.reviews.store') }}" method="POST" style="display:grid;gap:12px;">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
            <input name="author_name" placeholder="Author name" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
            <input name="stars" type="number" min="1" max="5" value="5" placeholder="Stars" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
            <input name="source_tag" placeholder="Source (e.g. Google)" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
        </div>
        <textarea name="body" placeholder="Review text" required rows="2" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);resize:vertical;"></textarea>
        <div style="display:flex;gap:12px;align-items:center;">
            <label style="display:flex;align-items:center;gap:8px;font-size:14px;color:var(--cream-2);">
                <input type="checkbox" name="is_featured" value="1" checked> Featured on site
            </label>
            <button type="submit" class="btn btn-gold btn-sm" style="margin-left:auto;">Add review</button>
        </div>
    </form>
</div>

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
                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" style="margin-top:8px;" onsubmit="return confirm('Delete this review?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--spice-400);">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
