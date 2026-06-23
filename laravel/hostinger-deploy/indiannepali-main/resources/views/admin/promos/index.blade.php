@extends('layouts.admin')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px;">
    <div>
        <h1 style="font-size:30px;font-weight:600;">Offers & promos</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">Manage live deals — combo meals, spend & save, reservation perks, and limited-time specials.</p>
    </div>
    <button type="button" class="btn btn-gold btn-sm" onclick="document.getElementById('add-offer-dialog')?.showModal()"><x-icon name="plus" :size="16"/> Add offer</button>
</div>

<dialog id="add-offer-dialog" style="width:min(980px,calc(100vw - 28px));border:1px solid var(--line);border-radius:14px;background:var(--ink-700);color:var(--cream);padding:0;box-shadow:var(--shadow-3);">
    <div style="padding:18px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;gap:10px;">
        <h3 style="font-size:20px;font-weight:600;margin:0;">Add offer</h3>
        <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('add-offer-dialog')?.close()">Cancel</button>
    </div>
    <div style="padding:18px;">
        @include('admin.promos.partials.form', ['promo' => null, 'formAction' => route('admin.promos.store'), 'method' => 'POST'])
    </div>
</dialog>

<div style="display:flex;flex-direction:column;gap:16px;">
    @forelse($promos as $promo)
        <div class="adm-card" style="padding:22px;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;margin-bottom:16px;">
                <div>
                    @include('admin.partials.badge', ['tone' => $promo->is_active ? 'green' : 'neutral', 'dot' => true, 'label' => $promo->is_active ? 'Live' : 'Hidden'])
                    <span style="margin-left:8px;color:var(--muted);font-size:13px;">{{ $offerTypes[$promo->offer_type] ?? $promo->offer_type }}</span>
                </div>
                <form action="{{ route('admin.promos.destroy', $promo) }}" method="POST" data-confirm="Delete this offer?">
                    @csrf @method('DELETE')
                    <button type="submit" style="width:34px;height:34px;border-radius:9px;background:transparent;border:1px solid var(--line);color:var(--sand);cursor:pointer;display:grid;place-items:center;"><x-icon name="trash" :size="16"/></button>
                </form>
            </div>
            @include('admin.promos.partials.form', ['promo' => $promo, 'formAction' => route('admin.promos.update', $promo), 'method' => 'PUT'])
        </div>
    @empty
        <div class="adm-card" style="padding:32px;text-align:center;color:var(--muted)">No offers yet. Create your first deal above.</div>
    @endforelse
</div>
@endsection

@push('scripts')
<script src="/js/admin-promos.js"></script>
@endpush
