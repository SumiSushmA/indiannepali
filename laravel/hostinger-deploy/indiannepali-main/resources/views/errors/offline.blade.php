<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline · Check Connection</title>
    <link rel="icon" href="/logo.png" type="image/png">
    <style>
        :root {
            --bg-a: #161616;
            --bg-b: #161616;
            --ink: #f8f0dc;
            --muted: #cdbba0;
            --line: rgba(201, 146, 42, 0.28);
            --btn-a: #c9922a;
            --btn-b: #a77722;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            background: var(--bg-a);
            color: var(--ink);
            display: grid;
            place-items: center;
            padding: 24px;
            overflow: hidden;
        }

        .bg-grid {
            position: fixed;
            inset: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(244, 236, 221, 0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(244, 236, 221, 0.035) 1px, transparent 1px);
            background-size: 72px 72px;
            opacity: .14;
        }

        .card {
            width: min(560px, 100%);
            text-align: center;
            background: rgba(24, 18, 16, 0.82);
            border: 1px solid var(--line);
            border-radius: 24px;
            backdrop-filter: blur(6px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.38);
            padding: 38px 28px 30px;
            position: relative;
            z-index: 1;
        }

        .icon-wrap {
            width: 146px;
            height: 146px;
            border-radius: 999px;
            border: 5px solid rgba(201, 146, 42, 0.75);
            margin: 0 auto 16px;
            display: grid;
            place-items: center;
        }

        .icon {
            font-size: 84px;
            line-height: 1;
            color: rgba(201, 146, 42, 0.9);
            font-weight: 600;
        }

        h1 {
            margin: 0;
            font-size: clamp(30px, 4.5vw, 42px);
            line-height: 1.15;
        }

        p {
            margin: 14px auto 24px;
            color: var(--muted);
            max-width: 420px;
            font-size: 17px;
            line-height: 1.6;
        }

        .btn {
            border: 0;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 260px;
            border-radius: 12px;
            color: #211405;
            padding: 12px 18px;
            font-size: 15px;
            font-weight: 700;
            background: linear-gradient(90deg, var(--btn-a), var(--btn-b));
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="bg-grid" aria-hidden="true"></div>
    <main class="card" role="main" aria-labelledby="offline-title">
        <div class="icon-wrap" aria-hidden="true">
            <div class="icon">!</div>
        </div>
        <h1 id="offline-title">You are currently offline</h1>
        <p>Please check your internet connection and try again.</p>
        <button type="button" class="btn" onclick="window.location.reload()">Retry</button>
    </main>
</body>
</html>
