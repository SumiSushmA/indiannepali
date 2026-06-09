@extends('layouts.customer')

@section('content')
@if(session('reserved') && $reservation)
    <div style="min-height:80vh;display:grid;place-items:center;padding:120px 24px;text-align:center">
        <div class="fade-up" style="max-width:480px">
            <div style="width:88px;height:88px;border-radius:999px;margin:0 auto 26px;background:var(--gold-glow);border:1px solid var(--gold-600);display:grid;place-items:center;color:var(--gold-400)">
                <x-icon name="check" :size="42" />
            </div>
            <h1 style="font-size:46px">Table reserved</h1>
            <p style="color:var(--sand);font-size:17px;line-height:1.65;margin-top:16px">See you soon! A confirmation is on its way to your inbox.</p>
            <div style="display:inline-flex;flex-wrap:wrap;justify-content:center;gap:16px;margin-top:24px;background:var(--ink-700);border:1px solid var(--line);border-radius:14px;padding:16px 26px">
                <span style="display:flex;gap:8px;align-items:center"><x-icon name="users" :size="17" color="var(--gold-400)" /> {{ $reservation['party'] }} guests</span>
                <span style="display:flex;gap:8px;align-items:center"><x-icon name="cal" :size="17" color="var(--gold-400)" /> {{ $reservation['date'] }}</span>
                <span style="display:flex;gap:8px;align-items:center"><x-icon name="clock" :size="17" color="var(--gold-400)" /> {{ $reservation['time'] }}</span>
            </div>
            <div style="margin-top:30px"><a href="{{ route('home') }}" class="btn btn-gold">Back to home</a></div>
        </div>
    </div>
@else
    <div class="cust-page-head cust-pad">
        <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">Reservations</div>
        <h1 style="font-size:clamp(38px,5vw,62px);line-height:1.03">Reserve your table</h1>
        <p style="color:var(--sand);font-size:17px;line-height:1.65;margin-top:18px">Book instantly — for an intimate dinner or the whole family. Large parties and the chef's counter, just ask.</p>
    </div>
    <div style="height:40px"></div>

    <form action="{{ route('reserve.store') }}" method="POST" style="max-width:1080px;margin:0 auto;padding:0 32px 110px">
        @csrf
        <div class="cust-reserve-grid">
            <div class="cust-card">
                <div style="margin-bottom:24px">
                    <div style="font-size:13px;color:var(--sand);font-weight:600;margin-bottom:12px">Party size</div>
                    <div style="display:flex;gap:8px;flex-wrap:wrap">
                        @foreach([1,2,3,4,5,6,7,8] as $n)
                            <label style="min-width:48px;width:48px;height:48px;border-radius:12px;cursor:pointer;font-weight:600;font-size:15px;display:grid;place-items:center;background:var(--ink-800);color:var(--cream);border:1px solid var(--line)">
                                <input type="radio" name="party" value="{{ $n }}" {{ old('party', 2) == $n ? 'checked' : '' }} style="position:absolute;opacity:0">{{ $n }}
                            </label>
                        @endforeach
                        <label style="padding:0 16px;height:48px;border-radius:12px;cursor:pointer;font-weight:600;display:grid;place-items:center;background:var(--ink-800);border:1px solid var(--line)">
                            <input type="radio" name="party" value="9" style="position:absolute;opacity:0"> 9+
                        </label>
                    </div>
                </div>
                <div style="margin-bottom:24px">
                    <div style="font-size:13px;color:var(--sand);font-weight:600;margin-bottom:12px">Date</div>
                    <div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:4px">
                        @foreach($dates as $d)
                            <label style="flex-shrink:0;width:64px;padding:12px 0;border-radius:12px;cursor:pointer;text-align:center;background:var(--ink-800);border:1px solid var(--line)">
                                <input type="radio" name="date" value="{{ $d['value'] }}" {{ old('date') === $d['value'] ? 'checked' : '' }} style="position:absolute;opacity:0">
                                <div style="font-size:11px;opacity:.8;text-transform:uppercase;letter-spacing:.06em">{{ $d['weekday'] }}</div>
                                <div style="font-size:22px;font-family:var(--serif);font-weight:600;line-height:1.1">{{ $d['day'] }}</div>
                                <div style="font-size:11px;opacity:.7">{{ $d['month'] }}</div>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <div style="font-size:13px;color:var(--sand);font-weight:600;margin-bottom:12px">Time</div>
                    <div style="display:grid;grid-template-columns:repeat(6,1fr);gap:8px">
                        @foreach($times as $t)
                            <label style="padding:11px 0;border-radius:12px;cursor:pointer;font-weight:600;font-size:15px;text-align:center;background:var(--ink-800);border:1px solid var(--line)">
                                <input type="radio" name="time" value="{{ $t }}" {{ old('time') === $t ? 'checked' : '' }} style="position:absolute;opacity:0">{{ $t }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="cust-card">
                <h3 style="font-size:24px;margin-bottom:6px">Your details</h3>
                <p style="color:var(--muted);font-size:14;margin-bottom:20px">We'll hold your table for 15 minutes.</p>
                <div style="display:grid;gap:14px">
                    <label class="cust-field"><span>Full name</span><input class="cust-inp" name="name" placeholder="Asha Gurung" required value="{{ old('name') }}"></label>
                    <label class="cust-field"><span>Phone</span><input class="cust-inp" name="phone" type="tel" placeholder="(415) 555-0140" required value="{{ old('phone') }}"></label>
                    <label class="cust-field"><span>Email</span><input class="cust-inp" name="email" type="email" placeholder="you@email.com" required value="{{ old('email') }}"></label>
                    <label class="cust-field"><span>Occasion (optional)</span>
                        <select class="cust-inp" name="occasion">
                            @foreach(['—', 'Birthday', 'Anniversary', 'Date night', 'Business', 'Celebration'] as $o)
                                <option value="{{ $o === '—' ? '' : $o }}" {{ old('occasion') === $o ? 'selected' : '' }}>{{ $o }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="cust-field"><span>Special requests</span><textarea class="cust-inp" name="notes" placeholder="Window table, high chair, allergies…" style="min-height:70px;resize:vertical">{{ old('notes') }}</textarea></label>
                </div>
                <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center;margin-top:20px">Confirm reservation</button>
            </div>
        </div>
    </form>
@endif
@endsection
