<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $site['restaurant_name'] ?? 'Indian Nepali Kitchen' }} @isset($pageTitle) · {{ $pageTitle }} @endisset</title>
    <link rel="icon" href="/logo.png" type="image/png">
    <link rel="stylesheet" href="/css/theme.css?v={{ filemtime(public_path('css/theme.css')) }}">
    <link rel="stylesheet" href="/css/customer.css?v={{ filemtime(public_path('css/customer.css')) }}">
    <link rel="stylesheet" href="/css/footer-cta.css?v={{ filemtime(public_path('css/footer-cta.css')) }}">
    @stack('styles')
    <link rel="stylesheet" href="/css/typography.css?v={{ filemtime(public_path('css/typography.css')) }}">
</head>
<body id="cust-root" data-page="{{ $bodyPage ?? 'page' }}" @if(session('open_cart')) data-open-cart="1" @endif>
    @include('customer.partials.nav')
    <main>@yield('content')</main>
    @include('customer.partials.footer')
    @include('customer.partials.cart-drawer')

    @if(session('success'))
        <div class="cust-flash">{{ session('success') }}</div>
    @endif

    <script src="/js/confirm-dialog.js"></script>
    <script src="/js/customer.js"></script>
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/sw.js').catch(function () {});
        });
    }
    </script>
    @stack('scripts')
</body>
</html>
