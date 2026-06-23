@php
$promo = $promo ?? null;
@endphp
<form action="{{ $formAction }}" method="POST" class="adm-promo-form" style="display:grid;gap:12px;">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <input name="badge" placeholder="Badge (e.g. Combo deal)" required value="{{ old('badge', $promo?->badge) }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
        <input name="price_label" placeholder="Price label (e.g. $14.99 or 6+ guests)" required value="{{ old('price_label', $promo?->price_label) }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
    </div>

    <input name="title" placeholder="Offer title" required value="{{ old('title', $promo?->title) }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
    <textarea name="detail" placeholder="What the customer gets" required rows="2" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);resize:vertical;">{{ old('detail', $promo?->detail) }}</textarea>
    <textarea name="terms" placeholder="Fine print (optional) — e.g. dine-in only, cannot combine offers" rows="2" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);resize:vertical;">{{ old('terms', $promo?->terms) }}</textarea>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <label style="display:grid;gap:6px;font-size:13px;color:var(--sand);">
            Offer type
            <select name="offer_type" class="adm-promo-offer-type" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:10px 14px;color:var(--cream);font-family:var(--sans);">
                @foreach($offerTypes as $value => $label)
                    <option value="{{ $value }}" @selected(old('offer_type', $promo?->offer_type ?? 'limited_time') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <label style="display:grid;gap:6px;font-size:13px;color:var(--sand);">
            Accent color
            <select name="accent" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:10px 14px;color:var(--cream);font-family:var(--sans);">
                <option value="gold" @selected(old('accent', $promo?->accent ?? 'gold') === 'gold')>Gold</option>
                <option value="spice" @selected(old('accent', $promo?->accent) === 'spice')>Spice</option>
                <option value="leaf" @selected(old('accent', $promo?->accent) === 'leaf')>Leaf</option>
            </select>
        </label>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <label style="display:grid;gap:6px;font-size:13px;color:var(--sand);">
            Button action
            <select name="cta_type" class="adm-promo-cta-type" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:10px 14px;color:var(--cream);font-family:var(--sans);">
                @foreach($ctaTypes as $value => $label)
                    <option value="{{ $value }}" @selected(old('cta_type', $promo?->cta_type ?? 'menu') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <input name="cta_label" placeholder="Button label (e.g. Order this combo)" value="{{ old('cta_label', $promo?->cta_label) }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);align-self:end;">
    </div>

    <div class="adm-promo-field adm-promo-menu-item" style="display:grid;gap:6px;">
        <label style="font-size:13px;color:var(--sand);">Menu item (for combo / order offers)</label>
        <select name="menu_item_slug" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:10px 14px;color:var(--cream);font-family:var(--sans);">
            <option value="">— Select item —</option>
            @foreach($menuItems as $item)
                <option value="{{ $item->slug }}" @selected(old('menu_item_slug', $promo?->menu_item_slug) === $item->slug)>{{ $item->name }}</option>
            @endforeach
        </select>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:12px;">
        <label class="adm-promo-field adm-promo-min-order" style="display:grid;gap:6px;font-size:13px;color:var(--sand);">
            Min order ($)
            <input name="min_order_amount" type="number" step="0.01" min="0" placeholder="40" value="{{ old('min_order_amount', $promo?->min_order_amount) }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:10px 14px;color:var(--cream);font-family:var(--sans);">
        </label>
        <label class="adm-promo-field adm-promo-min-party" style="display:grid;gap:6px;font-size:13px;color:var(--sand);">
            Min party size
            <input name="min_party_size" type="number" min="1" max="30" placeholder="6" value="{{ old('min_party_size', $promo?->min_party_size) }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:10px 14px;color:var(--cream);font-family:var(--sans);">
        </label>
        <label style="display:grid;gap:6px;font-size:13px;color:var(--sand);">
            Starts
            <input name="starts_at" type="date" value="{{ old('starts_at', $promo?->starts_at?->format('Y-m-d')) }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:10px 14px;color:var(--cream);font-family:var(--sans);">
        </label>
        <label style="display:grid;gap:6px;font-size:13px;color:var(--sand);">
            Ends
            <input name="ends_at" type="date" value="{{ old('ends_at', $promo?->ends_at?->format('Y-m-d')) }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:10px 14px;color:var(--cream);font-family:var(--sans);">
        </label>
    </div>

    <div style="display:flex;gap:12px;align-items:center;justify-content:flex-end;">
        <label style="display:flex;align-items:center;gap:8px;font-size:14px;color:var(--cream-2);">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $promo?->is_active ?? true))> Show on website
        </label>
        <button type="submit" class="btn btn-gold btn-sm">{{ $promo ? 'Save offer' : 'Add offer' }}</button>
    </div>
</form>
