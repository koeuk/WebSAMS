<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BELTEI - WebSAMS Admin</title>
    @php($faviconPath = \App\Models\Setting::get('favicon') ?: \App\Models\Setting::get('university_logo'))
    <link rel="icon" type="image/png" href="{{ $faviconPath ? '/storage/'.ltrim($faviconPath, '/') : '/logo1.png' }}">
    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
</head>
<body class="font-sans antialiased">
    @inertia
</body>
</html>
