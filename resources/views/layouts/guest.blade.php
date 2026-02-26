<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Login' }} | {{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/custom-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/guest.css') }}">
    @stack('styles')
</head>
<body class="guest-page">
    <div class="guest-wrapper {{ $wide ? 'guest-wrapper--wide' : '' }}">
        {{ $slot }}
    </div>
    <script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
