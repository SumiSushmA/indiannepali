<footer id="cust-footer" style="background:var(--ink-900);border-top:1px solid var(--line);margin-top:0">
    <div style="max-width:1240px;margin:0 auto;padding:72px 32px 40px">
        <div class="cust-foot-grid">
            <div>
                <x-logo :size="36" :href="route('home')" />
                <p style="color:var(--sand);font-size:14.5px;line-height:1.7;margin-top:20px;max-width:300px">{{ $site['footer_tagline'] ?? $content['Footer tagline'] ?? '' }}</p>
                <div style="display:flex;gap:10px;margin-top:22px">
                    @foreach([
                        ['ig', 'instagram_url'],
                        ['fb', 'facebook_url'],
                        ['wa', 'whatsapp_url'],
                    ] as [$icon, $key])
                        @if(!empty($site[$key]))
                        <a href="{{ $site[$key] }}" target="_blank" rel="noopener" style="width:40px;height:40px;border-radius:999px;border:1px solid var(--line);display:grid;place-items:center;color:var(--cream-2);text-decoration:none">
                            <x-icon :name="$icon" :size="18" />
                        </a>
                        @endif
                    @endforeach
                </div>
            </div>
            <div>
                <div style="font-size:12px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:var(--gold-600);margin-bottom:16px">Explore</div>
                <div style="display:flex;flex-direction:column;gap:11px">
                    @foreach([['Menu & Order', 'menu'], ['Reserve a Table', 'reserve'], ['Catering', 'catering'], ['Gallery', 'gallery'], ['Gift Cards', 'giftcards'], ['Offers', 'promos']] as [$label, $route])
                        <a href="{{ route($route) }}" style="color:var(--sand);font-size:14.5px;text-decoration:none">{{ $label }}</a>
                    @endforeach
                </div>
            </div>
            <div>
                <div style="font-size:12px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:var(--gold-600);margin-bottom:16px">Visit</div>
                <div style="display:flex;flex-direction:column;gap:11px">
                    @foreach([['About Us', 'about'], ['Hours & Location', 'contact'], ['Private Dining', 'catering'], ['Careers', 'contact']] as [$label, $route])
                        <a href="{{ route($route) }}" style="color:var(--sand);font-size:14.5px;text-decoration:none">{{ $label }}</a>
                    @endforeach
                </div>
            </div>
            <div>
                <div style="font-size:12px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:var(--gold-600);margin-bottom:16px">Find Us</div>
                <div style="color:var(--sand);font-size:14.5px;line-height:1.8">
                    <div>{{ $site['address'] ?? '13754 Aurora Ave N, Suite D' }}</div>
                    <div>{{ $site['city'] ?? 'Seattle, WA 98133' }}</div>
                    <div style="margin-top:10px;color:var(--cream-2)">{{ $site['phone'] ?? '(206) 397-3211' }}</div>
                    <div style="margin-top:14px;color:var(--cream)">{{ $site['hours'] ?? 'Daily · 10:00 AM – 9:30 PM' }}</div>
                    @if(!empty($site['closed_days']))
                        <div style="color:var(--muted)">{{ $site['closed_days'] }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:12px;padding-top:26px;color:var(--muted);font-size:13px">
            <div>© {{ date('Y') }} {{ $site['restaurant_name'] ?? 'Indian Nepali Kitchen' }}. All rights reserved.</div>
            <div style="display:flex;gap:22px">
                @foreach([
                    ['Privacy', 'privacy_url'],
                    ['Terms', 'terms_url'],
                    ['Accessibility', 'accessibility_url'],
                ] as [$label, $key])
                    @if(!empty($site[$key]))
                        <a href="{{ $site[$key] }}" target="_blank" rel="noopener" style="color:inherit;text-decoration:none">{{ $label }}</a>
                    @else
                        <span>{{ $label }}</span>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</footer>
