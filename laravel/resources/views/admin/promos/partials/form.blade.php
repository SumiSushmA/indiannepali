@php
$promo = $promo ?? null;
@endphp
<form action="{{ $formAction }}" method="POST" class="adm-promo-form">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    <div class="adm-promo-row adm-promo-row--2">
        <input name="badge" placeholder="Badge (e.g. Combo deal)" required value="{{ old('badge', $promo?->badge) }}">
        <input name="price_label" placeholder="Price label (e.g. $14.99 or 6+ guests)" required value="{{ old('price_label', $promo?->price_label) }}">
    </div>

    <input name="title" placeholder="Offer title" required value="{{ old('title', $promo?->title) }}">
    <textarea name="detail" placeholder="What the customer gets" required rows="2">{{ old('detail', $promo?->detail) }}</textarea>
    <textarea name="terms" placeholder="Fine print (optional) — e.g. dine-in only, cannot combine offers" rows="2">{{ old('terms', $promo?->terms) }}</textarea>

    <div class="adm-promo-row adm-promo-row--2">
        <label class="adm-promo-label">
            Offer type
            <select name="offer_type" class="adm-promo-offer-type">
                @foreach($offerTypes as $value => $label)
                    <option value="{{ $value }}" @selected(old('offer_type', $promo?->offer_type ?? 'limited_time') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <label class="adm-promo-label">
            Accent color
            <select name="accent">
                <option value="gold" @selected(old('accent', $promo?->accent ?? 'gold') === 'gold')>Gold</option>
                <option value="spice" @selected(old('accent', $promo?->accent) === 'spice')>Spice</option>
                <option value="leaf" @selected(old('accent', $promo?->accent) === 'leaf')>Leaf</option>
            </select>
        </label>
    </div>

    <div class="adm-promo-row adm-promo-row--2">
        <label class="adm-promo-label">
            Button action
            <select name="cta_type" class="adm-promo-cta-type">
                @foreach($ctaTypes as $value => $label)
                    <option value="{{ $value }}" @selected(old('cta_type', $promo?->cta_type ?? 'menu') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <input name="cta_label" placeholder="Button label (e.g. Order this combo)" value="{{ old('cta_label', $promo?->cta_label) }}">
    </div>

    <div class="adm-promo-field adm-promo-menu-item adm-promo-label">
        <span>Menu item (for combo / order offers)</span>
        <select name="menu_item_slug">
            <option value="">— Select item —</option>
            @foreach($menuItems as $item)
                <option value="{{ $item->slug }}" @selected(old('menu_item_slug', $promo?->menu_item_slug) === $item->slug)>{{ $item->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="adm-promo-row adm-promo-row--4">
        <label class="adm-promo-field adm-promo-min-order adm-promo-label">
            Min order ($)
            <input name="min_order_amount" type="number" step="0.01" min="0" placeholder="40" value="{{ old('min_order_amount', $promo?->min_order_amount) }}">
        </label>
        <label class="adm-promo-field adm-promo-min-party adm-promo-label">
            Min party size
            <input name="min_party_size" type="number" min="1" max="30" placeholder="6" value="{{ old('min_party_size', $promo?->min_party_size) }}">
        </label>
        <label class="adm-promo-label">
            Starts
            <input name="starts_at" type="date" value="{{ old('starts_at', $promo?->starts_at?->format('Y-m-d')) }}">
        </label>
        <label class="adm-promo-label">
            Ends
            <input name="ends_at" type="date" value="{{ old('ends_at', $promo?->ends_at?->format('Y-m-d')) }}">
        </label>
    </div>

    <div class="adm-promo-form__footer">
        <label class="adm-promo-check">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $promo?->is_active ?? true))> Show on website
        </label>
        <button type="submit" class="btn btn-gold btn-sm">{{ $promo ? 'Save offer' : 'Add offer' }}</button>
    </div>
</form>
