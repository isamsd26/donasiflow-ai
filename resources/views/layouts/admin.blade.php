<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $page['title'] ?? 'Admin' }} - {{ $admin['brand'] ?? 'CMS Admin' }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=oswald:400,500,600,700|instrument-sans:400,500,600,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#f1f5f9] text-[#16304f]">
        <div class="min-h-screen lg:grid lg:grid-cols-[280px_1fr]">
            <div class="fixed inset-0 z-40 hidden bg-[#0f172a]/55 lg:hidden" data-admin-sidebar-backdrop></div>
            @include('layouts.partials.admin-sidebar')

            <div class="min-h-screen min-w-0">
                @include('layouts.partials.admin-topbar')

                <main class="px-4 py-5 sm:px-6 lg:px-8">
                    @if (session('success'))
                        <div class="mb-5 rounded-2xl border border-[#cce7d9] bg-[#edf9f2] px-4 py-3 text-sm font-medium text-[#18794e]">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-5 rounded-2xl border border-[#f2d4d4] bg-[#fff2f2] px-4 py-3 text-sm font-medium text-[#b42318]">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-5 rounded-2xl border border-[#f2d4d4] bg-[#fff7f7] px-4 py-4 text-sm text-[#8f1d1d] shadow-[0_10px_18px_rgba(180,35,24,0.05)]">
                            <p class="font-semibold">Form belum bisa disimpan. Silakan cek bagian berikut:</p>
                            <ul class="mt-2 space-y-1">
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
