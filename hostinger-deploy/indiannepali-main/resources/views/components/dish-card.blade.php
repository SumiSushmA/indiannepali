@props(['item'])

<a href="{{ route('menu', ['q' => $item['name']]) }}" class="cust-dish-card cust-click-card">
    <div class="cust-dish-card-media">
        <x-food-image :item="$item" :h="200" />
        @if(!empty($item['popular']))
            <div style="position:absolute;top:14px;left:14px;background:rgba(10,10,10,.75);backdrop-filter:blur(6px);border:1px solid var(--brand-700);color:var(--brand-400);font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:6px 11px;border-radius:999px">★ Popular</div>
        @endif
    </div>
    <div style="padding:22px">
        <div style="display:flex;justify-content:space-between;align-items:baseline;gap:12px">
            <h4 style="font-size:21px;display:flex;align-items:center;gap:8px;margin:0">
                @if(!empty($item['veg']))
                    <x-icon name="veg" :size="13" color="var(--leaf-500)" />
                @endif
                {{ $item['name'] }}
            </h4>
            <span style="color:var(--brand-400);font-weight:600;font-family:var(--serif);font-size:21px">${{ $item['price'] }}</span>
        </div>
        <p style="color:var(--muted);font-size:14px;line-height:1.55;margin-top:8px;min-height:44px">{{ $item['desc'] }}</p>
        <form action="{{ route('cart.add') }}" method="POST" style="margin-top:16px" onclick="event.stopPropagation()">
            @csrf
            <input type="hidden" name="item_id" value="{{ $item['id'] }}">
            <button type="submit" class="btn btn-gold btn-sm" style="width:100%;justify-content:center">
                <x-icon name="plus" :size="16" /> Add to order
            </button>
        </form>
    </div>
</a>
