<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 · Page Not Found</title>
    <link rel="icon" href="/logo.png" type="image/png">
    <link rel="stylesheet" href="/css/theme.css">
    <link rel="stylesheet" href="/css/error-page.css">
</head>
<body class="err-page">
    <div class="err-page-scene">
        @include('errors.partials.cosmic-deco')

        <main class="err-page-content" role="main" aria-labelledby="err404-hero">
            <p class="err-page-label">Error</p>
            <h1 id="err404-hero" class="err-page-hero">404</h1>
            <p class="err-page-headline">This page is outside of the universe</p>
            <p class="err-page-desc">
                The page you are trying to access doesn't exist or has been moved.
                Try going back to our homepage.
            </p>
            <a href="{{ route('home') }}" class="err-page-btn">Go to homepage</a>
        </main>
    </div>
</body>
</html>
