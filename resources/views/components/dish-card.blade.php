@props(['item'])

@php
    $orderOnlineUrl = \App\Services\Toast\ToastConfiguration::onlineOrderingUrl();
    $menuHref = route('menu', ['q' => $item['name']]);
    $priceLabel = is_numeric($item['price'] ?? null)
        ? number_format((float) $item['price'], 2, '.', '')
        : ($item['price'] ?? '');
@endphp

<article class="cust-dish-card">
    <a href="{{ $menuHref }}" class="cust-dish-card-main">
        <div class="cust-dish-card-media">
            <x-food-image :item="$item" :h="200" />
            @if(!empty($item['popular']))
                <div class="cust-dish-card-badge">★ Popular</div>
            @endif
        </div>
        <div class="cust-dish-card-body">
            <div class="cust-dish-card-title-row">
                <h4 class="cust-dish-card-title">
                    @if(!empty($item['veg']))
                        <x-icon name="veg" :size="13" color="var(--leaf-500)" />
                    @endif
                    {{ $item['name'] }}
                </h4>
                <span class="cust-dish-card-price">${{ $priceLabel }}</span>
            </div>
            <p class="cust-dish-card-desc">{{ $item['desc'] }}</p>
        </div>
    </a>
    @if($orderOnlineUrl)
        <div class="cust-dish-card-action">
            <a href="{{ $orderOnlineUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-gold btn-sm cust-dish-card-btn">
                Order online <x-icon name="arrow" :size="16" />
            </a>
        </div>
    @else
        <form action="{{ route('cart.add') }}" method="POST" class="cust-dish-card-action">
            @csrf
            <input type="hidden" name="item_id" value="{{ $item['id'] }}">
            <button type="submit" class="btn btn-gold btn-sm cust-dish-card-btn">
                <x-icon name="plus" :size="16" /> Add to order
            </button>
        </form>
    @endif
</article>
