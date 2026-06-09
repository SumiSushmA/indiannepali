<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indian Nepali Kitchen — Admin Sign In</title>
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

        .adm-login-brand svg {
            margin-bottom: 14px;
        }

        .adm-login-brand h1 {
            font-family: var(--serif);
            font-weight: 600;
            font-size: 26px;
            color: var(--cream);
            margin: 0 0 6px;
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
            <svg width="48" height="48" viewBox="0 0 48 48">
                <circle cx="24" cy="24" r="22" fill="none" stroke="#d4a24e" stroke-width="1.4" opacity=".55"/>
                <circle cx="24" cy="24" r="16.5" fill="none" stroke="#d4a24e" stroke-width="1"/>
                <path d="M24 11 L33 24 L24 37 L15 24 Z" fill="none" stroke="#d4a24e" stroke-width="1.4"/>
                <circle cx="24" cy="24" r="4.4" fill="#d4a24e"/>
            </svg>
            <h1>Indian Nepali</h1>
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
                    <input type="password" id="password" name="password" required autocomplete="current-password">
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
</body>
</html>
