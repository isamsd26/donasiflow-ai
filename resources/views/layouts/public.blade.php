<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $site['title'] ?? config('app.name') }}</title>
        <meta name="description" content="{{ $site['description'] ?? '' }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=oswald:400,500,600,700|instrument-sans:400,500,600,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body>
        <div class="flex min-h-screen flex-col bg-slate-50 py-6">
            @include('layouts.partials.public-header')

            <main class="mt-4 flex-1">
                @yield('content')
            </main>

            @include('layouts.partials.public-footer')
        </div>

        @stack('scripts')
    </body>
</html>
