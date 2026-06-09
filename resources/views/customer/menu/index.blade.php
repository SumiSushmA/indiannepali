@extends('layouts.customer')

@section('content')
<div style="padding-bottom:100px">
    <div class="cust-page-head cust-pad">
        <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">Menu & online ordering</div>
        <h1 style="font-size:clamp(38px,5vw,62px);line-height:1.03">Order from our kitchen</h1>
        <p style="color:var(--sand);font-size:17px;line-height:1.65;margin-top:18px">Everything cooked to order. Choose pickup or delivery — we'll have it ready hot.</p>
    </div>

    <form method="GET" action="{{ route('menu') }}" class="cust-menu-sticky" id="menu-filter-form">
        <input type="hidden" name="mode" value="{{ $mode }}" id="menu-mode-input">
        @if($vegOnly)<input type="hidden" name="veg" value="1">@endif
        <div class="cust-section" style="padding:16px 32px;display:flex;gap:16px;align-items:center;flex-wrap:wrap">
            <div style="display:flex;background:var(--ink-700);border:1px solid var(--line);border-radius:999px;padding:4px">
                @foreach(['delivery', 'pickup'] as $m)
                    <button type="button" data-mode="{{ $m }}" class="menu-mode-btn" style="border:none;border-radius:999px;padding:9px 20px;cursor:pointer;font-weight:600;font-size:14px;font-family:var(--sans);text-transform:capitalize;display:flex;align-items:center;gap:7px;background:{{ $mode === $m ? 'var(--gold-600)' : 'transparent' }};color:{{ $mode === $m ? '#211405' : 'var(--cream-2)' }}">
                        <x-icon :name="$m === 'delivery' ? 'truck' : 'bag'" :size="16" /> {{ $m }}
                    </button>
                @endforeach
            </div>
            <div style="flex:1;min-width:200px;display:flex;align-items:center;gap:10px;background:var(--ink-700);border:1px solid var(--line);border-radius:999px;padding:0 18px">
                <x-icon name="search" :size="18" color="var(--muted)" />
                <input type="search" name="q" value="{{ $query }}" placeholder="Search dishes…" style="flex:1;background:none;border:none;outline:none;color:var(--cream);font-size:15px;padding:12px 0;font-family:var(--sans)">
            </div>
            @if($vegOnly)
                <a href="{{ route('menu', array_filter(['q' => $query, 'mode' => $mode])) }}" class="btn btn-ghost btn-sm" style="color:var(--leaf-500);border-color:var(--leaf-600)"><x-icon name="veg" :size="14" color="var(--leaf-500)" /> Veg only ✓</a>
            @else
                <a href="{{ route('menu', array_filter(['q' => $query, 'mode' => $mode, 'veg' => 1])) }}" class="btn btn-ghost btn-sm"><x-icon name="veg" :size="14" color="var(--leaf-500)" /> Veg only</a>
            @endif
            <button type="submit" class="btn btn-gold btn-sm">Search</button>
        </div>
        <div class="cust-section" style="padding:0 32px 14px;display:flex;gap:8px;overflow-x:auto">
            @foreach($categories as $cat)
                <a href="#cat-{{ $cat['id'] }}" class="btn btn-ghost btn-sm" style="flex-shrink:0;border-radius:999px;padding:8px 16px;font-size:13.5px">{{ $cat['name'] }}</a>
            @endforeach
        </div>
    </form>

    <div class="cust-section" style="padding:40px 32px 0">
        @foreach($categories as $cat)
            @php $catItems = array_filter($items, fn($i) => $i['cat'] === $cat['id']); @endphp
            @if(count($catItems))
                <div id="cat-{{ $cat['id'] }}" style="margin-bottom:56px;scroll-margin-top:150px">
                    <div style="border-bottom:1px solid var(--line);padding-bottom:16px;margin-bottom:26px">
                        <div style="display:flex;align-items:baseline;gap:14px;flex-wrap:wrap">
                            <h2 style="font-size:34px">{{ $cat['name'] }}</h2>
                            <span class="eyebrow">{{ $cat['tag'] }}</span>
                        </div>
                        <p style="color:var(--muted);font-size:15px;margin-top:6px">{{ $cat['desc'] }}</p>
                    </div>
                    <div class="cust-order-grid">
                        @foreach($catItems as $it)
                            <div style="display:flex;gap:16px;background:var(--ink-700);border:1px solid var(--line);border-radius:14px;padding:14px">
                                <x-food-image :item="$it" style="width:96px;height:96px;flex-shrink:0" :r="10" />
                                <div style="flex:1;min-width:0;display:flex;flex-direction:column">
                                    <div style="display:flex;justify-content:space-between;gap:10px;align-items:baseline">
                                        <h4 style="font-size:18.5px;display:flex;align-items:center;gap:7px;margin:0">
                                            @if($it['veg'])<x-icon name="veg" :size="12" color="var(--leaf-500)" />@endif
                                            {{ $it['name'] }}
                                            @if(($it['spice'] ?? 0) > 1)
                                                @for($s = 0; $s < $it['spice']; $s++)<x-icon name="flame" :size="11" color="var(--spice-500)" />@endfor
                                            @endif
                                        </h4>
                                        <span style="color:var(--gold-400);font-weight:600;font-family:var(--serif);font-size:19px">${{ $it['price'] }}</span>
                                    </div>
                                    <p style="color:var(--muted);font-size:13.5;line-height:1.5;margin:6px 0 0">{{ $it['desc'] }}</p>
                                    <div style="margin-top:auto;padding-top:12px;display:flex;align-items:center;gap:10px">
                                        @if(!empty($it['popular']))
                                            <span style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--gold-500)">★ Popular</span>
                                        @endif
                                        <form action="{{ route('cart.add') }}" method="POST" style="margin-left:auto">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $it['id'] }}">
                                            <button type="submit" class="btn btn-gold btn-sm"><x-icon name="plus" :size="15" /> Add</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.menu-mode-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('menu-mode-input').value = btn.dataset.mode;
        document.getElementById('menu-filter-form').submit();
    });
});
</script>
@endpush
