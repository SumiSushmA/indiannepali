<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline · Check Connection</title>
    <link rel="icon" href="/logo.png" type="image/png">
    <link rel="stylesheet" href="/css/theme.css">
    <link rel="stylesheet" href="/css/error-page.css">
</head>
<body class="err-page">
    <div class="err-page-scene">
        @include('errors.partials.cosmic-deco')

        <main class="err-page-content" role="main" aria-labelledby="offline-title">
            <p class="err-page-label">Offline</p>
            <div class="err-page-hero err-page-hero--symbol" aria-hidden="true">!</div>
            <p id="offline-title" class="err-page-headline">You are outside of the connection</p>
            <p class="err-page-desc">
                The page you are trying to access needs an internet connection.
                Please check your network and try again.
            </p>
            <button type="button" class="err-page-btn" onclick="window.location.reload()">Try again</button>
        </main>
    </div>
</body>
</html>
