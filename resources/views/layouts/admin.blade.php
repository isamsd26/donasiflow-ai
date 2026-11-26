<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $page['title'] ?? 'Admin' }} - {{ $admin['brand'] ?? 'DonasiFlow Admin' }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=newsreader:400,500,600,700,400i|plus-jakarta-sans:400,500,600,700|jetbrains-mono:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#F2F1EC] text-ink">
        <div class="min-h-screen lg:grid lg:grid-cols-[248px_1fr]">
            <div class="fixed inset-0 z-40 hidden bg-ink/45 backdrop-blur-[1px] lg:hidden" data-admin-sidebar-backdrop></div>
            @include('layouts.partials.admin-sidebar')

            <div class="flex min-h-screen min-w-0 flex-col">
                @include('layouts.partials.admin-topbar')

                <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                    @if (session('success'))
                        <div class="mb-5 rounded-lg border border-brand/25 bg-brand-soft px-4 py-3 text-sm font-medium text-brand">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-5 rounded-lg border border-danger/25 bg-danger-soft px-4 py-3 text-sm font-medium text-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-5 rounded-lg border border-danger/25 bg-danger-soft px-4 py-4 text-sm text-danger">
                            <p class="font-semibold">Form belum bisa disimpan. Periksa bagian berikut:</p>
                            <ul class="mt-2 space-y-1 text-xs">
                                @foreach ($errors->all() as $error)
                                    <li>- {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
