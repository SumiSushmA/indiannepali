@extends('layouts.customer')

@section('content')
<div class="cust-hub-wrap" style="background:radial-gradient(120% 90% at 80% -10%,#1c130c,var(--ink-800) 55%);min-height:100vh;padding-top:120px">
    <div class="mark" style="display:flex;align-items:center;gap:14px">
        <svg viewBox="0 0 48 48" style="width:46px;height:46px"><circle cx="24" cy="24" r="22" fill="none" stroke="#d4a24e" stroke-width="1.4" opacity=".55"/><circle cx="24" cy="24" r="16.5" fill="none" stroke="#d4a24e" stroke-width="1"/><path d="M24 11 L33 24 L24 37 L15 24 Z" fill="none" stroke="#d4a24e" stroke-width="1.4"/><circle cx="24" cy="24" r="4.4" fill="#d4a24e"/></svg>
        <div>
            <div style="font-family:var(--serif);font-weight:600;font-size:24px;line-height:1">{{ $site['restaurant_name'] ?? 'Indian Nepali Kitchen' }}</div>
            <div style="font-size:11px;letter-spacing:.42em;text-transform:uppercase;color:var(--gold-600);margin-top:5px">Design System & Prototypes</div>
        </div>
    </div>

    <div style="margin:48px 0 30px;max-width:640px">
        <div class="eyebrow" style="margin-bottom:16px">Fine-dining luxury · Indian & Nepali</div>
        <h1 style="font-size:clamp(40px,6vw,68px);line-height:1.02">A warm, elegant home for the Kitchen — and a powerful place to run it.</h1>
        <p style="color:var(--sand);font-size:17px;line-height:1.65;margin-top:18px">Two connected experiences: a high-converting customer website for ordering, reservations and catering, and a SaaS-style admin to run service end-to-end.</p>
    </div>

    <div class="cust-hub-grid2" style="margin-bottom:70px">
        <a class="cust-hub-card" href="{{ route('home') }}">
            <div class="ph" style="height:200px;position:relative;overflow:hidden;border:none;border-radius:0">
                <span>customer site preview</span>
                <div style="position:absolute;inset:0;background:linear-gradient(120deg,rgba(13,10,8,.85),transparent)"></div>
                <div style="position:absolute;left:24px;bottom:22px">
                    <div style="font-family:var(--serif);font-size:30px;font-weight:600">Where the<br><em style="color:var(--gold-400)">Himalayas</em> meet…</div>
                </div>
            </div>
            <div style="padding:24px;display:flex;justify-content:space-between;align-items:center;gap:16px">
                <div>
                    <div style="font-family:var(--serif);font-size:24px;font-weight:600">Customer Website</div>
                    <div style="color:var(--muted);font-size:14px;margin-top:5px">Home · Order · Reserve · Catering · Gallery · Offers</div>
                </div>
                <span class="btn btn-gold btn-sm">Open</span>
            </div>
        </a>
        <a class="cust-hub-card" href="{{ route('admin.dashboard') }}">
            <div style="height:200px;background:var(--ink-850);border-bottom:1px solid var(--line);display:flex">
                <div style="width:64px;background:var(--ink-900);border-right:1px solid var(--line);padding:14px 10px;display:flex;flex-direction:column;gap:8px">
                    <div style="width:24px;height:24px;border-radius:7px;background:var(--gold-glow);border:1px solid var(--gold-700)"></div>
                    <div style="height:7px;border-radius:4px;background:var(--ink-600)"></div>
                    <div style="height:7px;border-radius:4px;background:var(--ink-600);width:70%"></div>
                    <div style="height:7px;border-radius:4px;background:var(--gold-700)"></div>
                </div>
                <div style="flex:1;padding:18px;display:grid;grid-template-columns:1fr 1fr;gap:10px;align-content:start">
                    <div style="height:48px;border-radius:9px;background:var(--ink-700);border:1px solid var(--line)"></div>
                    <div style="height:48px;border-radius:9px;background:var(--ink-700);border:1px solid var(--line)"></div>
                    <div style="grid-column:1/-1;height:62px;border-radius:9px;background:var(--ink-700);border:1px solid var(--line);display:flex;align-items:flex-end;gap:5px;padding:10px">
                        <div style="flex:1;height:30%;background:var(--gold-700);border-radius:3px"></div>
                        <div style="flex:1;height:55%;background:var(--gold-600);border-radius:3px"></div>
                        <div style="flex:1;height:80%;background:var(--gold-500);border-radius:3px"></div>
                        <div style="flex:1;height:45%;background:var(--gold-700);border-radius:3px"></div>
                    </div>
                </div>
            </div>
            <div style="padding:24px;display:flex;justify-content:space-between;align-items:center;gap:16px">
                <div>
                    <div style="font-family:var(--serif);font-size:24px;font-weight:600">Admin Dashboard</div>
                    <div style="color:var(--muted);font-size:14px;margin-top:5px">Analytics · Orders · Reservations · POS · Users</div>
                </div>
                <span class="btn btn-gold btn-sm">Open</span>
            </div>
        </a>
    </div>

    <div class="eyebrow" style="margin-bottom:24px">The system</div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:start" class="cust-hub-grid2">
        <div>
            <h3 style="font-size:22px;margin-bottom:16px">Palette</h3>
            <div style="display:flex;gap:0;border-radius:12px;overflow:hidden;border:1px solid var(--line);margin-bottom:10px">
                @foreach([['#0d0a08','ink-900'],['#17120e','ink-800'],['#241c16','ink-700'],['#38291c','ink-600']] as [$bg, $label])
                    <div style="flex:1;height:76px;background:{{ $bg }};display:flex;align-items:flex-end;padding:8px;font-size:10px;font-family:ui-monospace,monospace;color:rgba(255,255,255,.7)">{{ $label }}</div>
                @endforeach
            </div>
            <div style="display:flex;gap:0;border-radius:12px;overflow:hidden;border:1px solid var(--line)">
                @foreach([['#e6bd78','gold-400','#3a2510'],['#c8852f','gold-600','#2a1808'],['#9c3b25','spice-600','#fff'],['#4f7d44','leaf-600','#fff']] as [$bg, $label, $fg])
                    <div style="flex:1;height:76px;background:{{ $bg }};color:{{ $fg }};display:flex;align-items:flex-end;padding:8px;font-size:10px;font-family:ui-monospace,monospace">{{ $label }}</div>
                @endforeach
            </div>
        </div>
        <div>
            <h3 style="font-size:22px;margin-bottom:16px">Type</h3>
            <div style="border:1px solid var(--line);border-radius:14px;padding:22px;background:var(--ink-700)">
                <div style="font-family:var(--serif);font-size:40px;font-weight:600;line-height:1">Cormorant Garamond</div>
                <div style="color:var(--muted);font-size:12px;margin:6px 0 18px;font-family:ui-monospace,monospace">DISPLAY · HEADLINES · SERIF</div>
                <div style="font-family:var(--sans);font-size:18px;font-weight:600">Hanken Grotesk</div>
                <div style="font-family:var(--sans);color:var(--sand);font-size:14px;line-height:1.6;margin-top:6px">Clean, legible body text for menus, tables and long-form copy across both experiences.</div>
            </div>
        </div>
    </div>

    <div style="margin-top:40px">
        <h3 style="font-size:22px;margin-bottom:16px">Components</h3>
        <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:center">
            <a href="{{ route('menu') }}" class="btn btn-gold">Order Online</a>
            <a href="{{ route('reserve') }}" class="btn btn-ghost">Reserve Table</a>
            <span class="btn btn-spice btn-sm">Save 20%</span>
            <span style="display:inline-flex;align-items:center;gap:7px;background:var(--ink-700);border:1px solid var(--line);border-radius:999px;padding:7px 14px;font-size:13px;color:var(--cream-2)"><span style="width:7px;height:7px;border-radius:99px;background:var(--leaf-500)"></span> Confirmed</span>
            <span style="display:inline-flex;align-items:center;gap:7px;background:var(--ink-700);border:1px solid var(--gold-700);border-radius:999px;padding:7px 14px;font-size:13px;color:var(--gold-400)">★ Popular</span>
        </div>
    </div>

    <div style="margin-top:70px;padding-top:26px;border-top:1px solid var(--line);color:var(--muted);font-size:13px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px">
        <span>© {{ date('Y') }} {{ $site['restaurant_name'] ?? 'Indian Nepali Kitchen' }} · Laravel</span>
        <span>Customer site · Admin dashboard · Design hub</span>
    </div>
</div>
@endsection
