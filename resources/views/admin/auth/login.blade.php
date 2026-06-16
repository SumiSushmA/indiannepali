<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indian Nepali Kitchen — Admin Sign In</title>
    <link rel="icon" href="/logo.png" type="image/png">
    <link rel="stylesheet" href="/css/theme.css">
    <style>
        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            background:
                radial-gradient(ellipse 80% 60% at 50% -10%, rgba(200,133,47,.12), transparent),
                var(--ink-900);
            padding: 24px;
        }

        .adm-login-wrap {
            width: 100%;
            max-width: 420px;
        }

        .adm-login-brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .adm-login-brand .cust-logo {
            justify-content: center;
            margin-bottom: 14px;
        }

        .adm-login-brand p {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .42em;
            text-transform: uppercase;
            color: var(--gold-500);
            margin: 0;
        }

        .adm-login-card {
            background: var(--ink-800);
            border: 1px solid var(--line);
            border-radius: var(--radius-l);
            padding: 32px 28px;
            box-shadow: var(--shadow-3);
        }

        .adm-login-card h2 {
            font-family: var(--serif);
            font-size: 22px;
            font-weight: 600;
            color: var(--cream);
            margin: 0 0 6px;
        }

        .adm-login-card .subtitle {
            font-size: 14px;
            color: var(--muted);
            margin: 0 0 28px;
        }

        .adm-login-field {
            margin-bottom: 18px;
        }

        .adm-login-field label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--sand);
            margin-bottom: 8px;
        }

        .adm-login-field input[type="email"],
        .adm-login-field input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            background: var(--ink-700);
            border: 1px solid var(--line);
            border-radius: var(--radius-s);
            color: var(--cream);
            font-family: var(--sans);
            font-size: 15px;
            transition: border-color .15s, box-shadow .15s;
        }

        .adm-login-pass-wrap {
            position: relative;
            width: 100%;
        }

        .adm-login-pass-wrap input {
            width: 100%;
            padding-right: 48px !important;
        }

        .adm-login-pass-toggle {
            position: absolute;
            top: 50%;
            right: 8px;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            border: none;
            border-radius: 8px;
            background: transparent;
            color: var(--muted);
            cursor: pointer;
            display: grid;
            place-items: center;
        }

        .adm-login-pass-toggle:hover {
            color: var(--cream);
            background: rgba(255, 255, 255, .04);
        }

        .adm-login-field input:focus {
            outline: none;
            border-color: var(--gold-600);
            box-shadow: var(--gold-ring);
        }

        .adm-login-remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            font-size: 14px;
            color: var(--sand);
        }

        .adm-login-remember input {
            accent-color: var(--gold-600);
        }

        .adm-login-submit {
            width: 100%;
            padding: 13px 20px;
            background: linear-gradient(135deg, var(--gold-600), var(--gold-700));
            border: none;
            border-radius: var(--radius-s);
            color: #fff;
            font-family: var(--sans);
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity .15s, transform .1s;
        }

        .adm-login-submit:hover {
            opacity: .92;
        }

        .adm-login-submit:active {
            transform: scale(.99);
        }

        .adm-login-error {
            background: rgba(156,59,37,.15);
            border: 1px solid rgba(156,59,37,.35);
            border-radius: var(--radius-s);
            color: #e8a090;
            font-size: 14px;
            padding: 12px 14px;
            margin-bottom: 20px;
        }

        .adm-login-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: var(--faint);
        }
    </style>
</head>
<body>
    <div class="adm-login-wrap">
        <div class="adm-login-brand">
            <x-logo :size="52" />
            <p>Kitchen Admin</p>
        </div>

        <div class="adm-login-card">
            <h2>Sign in</h2>
            <p class="subtitle">Enter your credentials to access the dashboard.</p>

            @if($errors->any())
                <div class="adm-login-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                <div class="adm-login-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                </div>

                <div class="adm-login-field">
                    <label for="password">Password</label>
                    <div class="adm-login-pass-wrap">
                        <input type="password" id="password" name="password" required autocomplete="current-password">
                        <button type="button" class="adm-login-pass-toggle" aria-label="Show password" id="adm-login-pass-toggle">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <path id="adm-login-pass-icon" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7-10-7Z M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <label class="adm-login-remember">
                    <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>

                <button type="submit" class="adm-login-submit">Sign in</button>
            </form>
        </div>

        <p class="adm-login-footer">&copy; {{ date('Y') }} Indian Nepali Kitchen</p>
    </div>
<script>
(() => {
    const input = document.getElementById('password');
    const btn = document.getElementById('adm-login-pass-toggle');
    const icon = document.getElementById('adm-login-pass-icon');
    if (!input || !btn || !icon) return;

    const eye = 'M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z';
    const eyeOff = 'M17.9 17.9A10 10 0 0 1 12 20C5 20 1 12 1 12a18.8 18.8 0 0 1 5.1-7.1M9.9 9.9a3 3 0 1 0 4.2 4.2M22 12s-3.5-7-10-7a10 10 0 0 0-5.3 1.5M3 3l18 18';

    btn.addEventListener('click', () => {
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        icon.setAttribute('d', show ? eyeOff : eye);
    });
})();
</script>
</body>
</html>
