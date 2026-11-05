<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Admin Login')</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=oswald:400,500,600,700|instrument-sans:400,500,600,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[radial-gradient(circle_at_top,#dbeafe_0%,#f1f5f9_38%,#e5edf6_100%)] text-[#16304f]">
        <main class="flex min-h-screen items-center justify-center px-4 py-10">
            @yield('content')
        </main>
    </body>
</html>
