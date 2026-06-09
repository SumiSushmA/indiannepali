<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $site['restaurant_name'] ?? 'Indian Nepali Kitchen' }} — Admin @isset($pageTitle) · {{ $pageTitle }} @endisset</title>
    <link rel="stylesheet" href="/css/theme.css">
    <link rel="stylesheet" href="/css/admin.css">
    @stack('styles')
</head>
<body>
<div class="adm-app">
    @include('admin.partials.sidebar', ['active' => $active ?? 'overview', 'badges' => $badges ?? []])
    <div class="adm-main-wrap">
        @include('admin.partials.topbar')
        <main id="adm-main">
            @yield('content')
        </main>
    </div>
</div>
<script>
document.getElementById('adm-burger')?.addEventListener('click', () => {
    document.getElementById('adm-sidebar')?.classList.add('open');
    document.getElementById('adm-scrim')?.classList.add('open');
});
document.getElementById('adm-scrim')?.addEventListener('click', () => {
    document.getElementById('adm-sidebar')?.classList.remove('open');
    document.getElementById('adm-scrim')?.classList.remove('open');
});
</script>
@stack('scripts')
</body>
</html>
