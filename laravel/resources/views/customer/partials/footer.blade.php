<div class="cust-site-end">
    <div class="cust-site-end__bg" aria-hidden="true">
        <img src="{{ asset('Group 1171275134.svg') }}" alt="">
    </div>

    <section class="cust-prefooter">
        <div class="cust-prefooter__inner">
            <h2 class="cust-prefooter__title">Ready for Seattle's best <em>momo?</em></h2>
            <p class="cust-prefooter__sub">
                {{ $content['Delivery blurb'] ?? 'Order online for pickup or delivery — hand-pleated momo, tandoori, curries, and Nepali thali from our kitchen on Aurora Avenue.' }}
            </p>
            <a href="{{ \App\Services\Toast\ToastConfiguration::onlineOrderingUrl() ?: route('menu') }}" class="cust-prefooter__btn">
                Order Online <x-icon name="arrow" :size="17" />
            </a>
        </div>
    </section>

    <footer id="cust-footer" class="cust-foot-shell">
        <div class="cust-foot-card">
            <div class="cust-foot-card__grid" aria-hidden="true"></div>
            <div class="cust-foot-card__inner">
                <div class="cust-foot-head">
                    <div class="cust-foot-brand">
                        <x-logo :size="34" :href="route('home')" :show-text="true" />
                        <span class="cust-foot-kicker">Indian & Nepali cuisine · Seattle</span>
                    </div>
                    <p class="cust-foot-lead">{{ $site['footer_tagline'] ?? $content['Footer tagline'] ?? '' }}</p>
                </div>

                <div class="cust-foot-mid">
                    <div class="cust-foot-col">
                        <h4>Restaurant</h4>
                        @foreach([['About Us', 'about'], ['Gallery', 'gallery'], ['Contact', 'contact']] as [$label, $route])
                            <a href="{{ route($route) }}">{{ $label }}</a>
                        @endforeach
                    </div>
                    <div class="cust-foot-col">
                        <h4>Order</h4>
                        @php
                            use App\Services\Toast\ToastConfiguration;
                            $footerOrderLinks = [
                                ['Menu & Order', ToastConfiguration::onlineOrderingUrl() ?: route('menu')],
                                ['Reserve a Table', ToastConfiguration::reservationUrl() ?: route('reserve')],
                                ['Catering', ToastConfiguration::cateringUrl() ?: route('catering')],
                                ['Gift Cards', ToastConfiguration::giftCardsUrl() ?: route('giftcards')],
                                ['Offers', route('promos')],
                            ];
                        @endphp
                        @foreach($footerOrderLinks as [$label, $href])
                            <a href="{{ $href }}">{{ $label }}</a>
                        @endforeach
                    </div>
                    <div class="cust-foot-col">
                        <h4>Visit</h4>
                        <div style="font-size:14px;color:var(--sand);line-height:1.7">
                            <div>{{ $site['address'] ?? '13754 Aurora Ave N, Suite D' }}</div>
                            <div>{{ $site['city'] ?? 'Seattle, WA 98133' }}</div>
                            <div style="margin-top:8px;color:var(--cream-2)">{{ $site['phone'] ?? '(206) 397-3211' }}</div>
                        </div>
                    </div>
                    <div class="cust-foot-social">
                        @foreach([
                            ['fb', 'facebook_url'],
                            ['ig', 'instagram_url'],
                            ['wa', 'whatsapp_url'],
                        ] as [$icon, $key])
                            @if(!empty($site[$key]))
                                <a href="{{ $site[$key] }}" target="_blank" rel="noopener" aria-label="{{ $icon }}">
                                    <x-icon :name="$icon" :size="17" />
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="cust-foot-base">
                    <p>© {{ date('Y') }} {{ $site['restaurant_name'] ?? 'Indian-Nepali Kitchen' }}. All rights reserved.</p>
                    <p class="cust-foot-credit">Developed by: <a href="https://www.linkedin.com/in/sushma-sharma-123943293/" target="_blank" rel="noopener noreferrer">Sushma Sharma</a></p>
                </div>
            </div>
        </div>
    </footer>
</div>
