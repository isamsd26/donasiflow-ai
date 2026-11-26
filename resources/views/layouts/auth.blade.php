<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Admin Login')</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=newsreader:400,500,600,700,400i|plus-jakarta-sans:400,500,600,700|jetbrains-mono:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-canvas text-ink">
        <main class="flex min-h-screen items-center justify-center px-4 py-10">
            @yield('content')
        </main>
    </body>
</html>
