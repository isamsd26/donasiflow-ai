<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ $siteName ?? 'DonasiFlow Nusantara' }}</title>
    <meta name="description" content="@yield('meta_description', 'Halaman kesalahan resmi platform donasi DonasiFlow Nusantara.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=oswald:500,600,700|plus-jakarta-sans:400,500,600,700" rel="stylesheet" />

    <!-- Tailwind & App Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .error-glow {
            position: absolute;
            border-radius: 9999px;
            filter: blur(80px);
            opacity: 0.35;
            pointer-events: none;
        }
    </style>
</head>
@php
    $siteName = 'DonasiFlow Nusantara';
    $siteTagline = 'Campaign stories for real-world impact';
    $adminWa = '081200001234';
    $adminEmail = 'halo@donasiflow.org';

    try {
        if (class_exists(\App\Models\Setting::class)) {
            $siteName = \App\Models\Setting::where('key', 'site_name')->first()?->value['value'] ?? $siteName;
            $siteTagline = \App\Models\Setting::where('key', 'site_tagline')->first()?->value['value'] ?? $siteTagline;
            $adminWa = \App\Models\Setting::where('key', 'whatsapp')->first()?->value['value'] ?? $adminWa;
            $adminEmail = \App\Models\Setting::where('key', 'contact_email')->first()?->value['value'] ?? $adminEmail;
        }
    } catch (\Throwable $e) {
        // Fallback safely to defaults if database is unreachable
    }

    $cleanWa = preg_replace('/[^\d]/', '', (string) $adminWa);
    if (str_starts_with($cleanWa, '0')) {
        $cleanWa = '62' . substr($cleanWa, 1);
    }
    $waHelpUrl = $cleanWa ? 'https://wa.me/' . $cleanWa . '?text=' . rawurlencode('Halo Admin DonasiFlow Nusantara, saya mengalami kendala teknis (Kode: ' . trim($__env->yieldContent('code', 'Error')) . ') saat mengakses website donasi. Mohon dibantu.') : null;
@endphp
<body class="flex min-h-screen flex-col bg-[#0b1329] text-[#1e293b] antialiased selection:bg-emerald-500 selection:text-white relative overflow-x-hidden font-sans">

    <!-- Background Ambient Glow -->
    <div class="error-glow -top-32 -left-32 h-96 w-96 bg-emerald-500/30"></div>
    <div class="error-glow top-1/2 -right-32 h-96 w-96 bg-teal-600/25"></div>
    <div class="error-glow -bottom-32 left-1/3 h-96 w-96 bg-blue-600/20"></div>

    <!-- Official Header -->
    <header class="relative z-10 w-full border-b border-white/10 bg-slate-900/60 backdrop-blur-md">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#059669,#047857)] text-white shadow-md shadow-emerald-900/40 transition group-hover:scale-105">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-.778.099-1.533.284-2.253" />
                    </svg>
                </div>
                <div>
                    <span class="block font-display text-xl font-bold tracking-tight text-white transition group-hover:text-emerald-300 sm:text-2xl leading-none">
                        {{ $siteName }}
                    </span>
                    <span class="mt-0.5 block text-[10px] uppercase tracking-[0.24em] text-emerald-300/80">
                        Halaman Resmi Yayasan
                    </span>
                </div>
            </a>

            <div class="flex items-center gap-2">
                <a href="{{ url('/') }}" class="hidden sm:inline-flex rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold text-white transition hover:bg-white/20">
                    Beranda
                </a>
                <a href="{{ url('/campaign') }}" class="hidden sm:inline-flex rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold text-white transition hover:bg-white/20">
                    Program Campaign
                </a>
                <a href="{{ url('/donation/check') }}" class="inline-flex rounded-full bg-pm-blue px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-600">
                    Cek Status Donasi
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="relative z-10 flex flex-1 items-center justify-center px-4 py-10 sm:py-16">
        <div class="w-full max-w-3xl">
            <!-- Card Container -->
            <div class="overflow-hidden rounded-[32px] border border-white/15 bg-white/95 p-6 shadow-[0_25px_60px_rgba(0,0,0,0.35)] backdrop-blur-xl sm:p-10 md:p-12">

                <!-- Badge & Status Header -->
                <div class="flex flex-col items-center text-center">
                    <div class="inline-flex items-center gap-2 rounded-full border px-3.5 py-1.5 text-xs font-semibold uppercase tracking-wider @yield('badge_style', 'border-emerald-200 bg-emerald-50 text-emerald-800')">
                        <span class="inline-block h-2 w-2 rounded-full @yield('dot_style', 'bg-emerald-500') animate-pulse"></span>
                        @yield('badge_text', 'Layanan Resmi Donasi')
                    </div>

                    <!-- Glowing Code -->
                    <div class="mt-6 flex items-center justify-center">
                        <div class="relative">
                            <h1 class="font-display text-8xl font-black tracking-tight sm:text-9xl md:text-[10rem] leading-none @yield('code_style', 'bg-[linear-gradient(135deg,#059669,#047857,#1e293b)] bg-clip-text text-transparent')">
                                @yield('code', '500')
                            </h1>
                        </div>
                    </div>

                    <!-- Title -->
                    <h2 class="mt-4 font-display text-2xl font-bold tracking-tight text-[#1e293b] sm:text-3xl md:text-4xl">
                        @yield('heading', 'Terjadi Kendala pada Sistem')
                    </h2>

                    <!-- Description -->
                    <p class="mt-3 max-w-xl text-sm leading-relaxed text-[#475569] sm:text-base sm:leading-7">
                        @yield('message', 'Mohon maaf atas ketidaknyamanan Anda. Sistem kami sedang menangani permintaan ini. Silakan ikuti instruksi bantuan di bawah.')
                    </p>

                    <!-- Dynamic Extra Content Slot -->
                    @yield('extra_content')

                    <!-- Action Buttons -->
                    <div class="mt-8 flex w-full flex-wrap items-center justify-center gap-3">
                        @section('actions')
                            <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-pm-blue px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-700/25 transition hover:bg-emerald-600 hover:shadow-emerald-700/35">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Kembali ke Beranda
                            </a>

                            <button type="button" onclick="window.location.reload()" class="inline-flex items-center justify-center gap-2 rounded-full border border-[#cbd5e1] bg-white px-5 py-3.5 text-sm font-semibold text-[#334155] shadow-sm transition hover:bg-slate-50">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Coba Lagi
                            </button>

                            <a href="{{ url('/donation/check') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-[#cbd5e1] bg-[#f8fafc] px-5 py-3.5 text-sm font-semibold text-[#334155] transition hover:bg-slate-100">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Cek Status Donasi
                            </a>
                        @show
                    </div>
                </div>

                <!-- Emergency Notice & Trust Box -->
                <div class="mt-8 rounded-2xl border border-emerald-100 bg-emerald-50/70 p-4 text-xs sm:text-sm text-emerald-900">
                    <div class="flex items-start gap-3">
                        <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-200/80 text-emerald-800">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-emerald-950">Jaminan Keamanan Donasi Anda</p>
                            <p class="mt-1 text-emerald-800/90 leading-relaxed">
                                Jika Anda sedang mengisi form donasi atau telah melakukan transfer rekening, data dan donasi Anda tetap aman di sistem kami.
                                @if($waHelpUrl)
                                    Bila butuh bantuan langsung atau konfirmasi manual, silakan <a href="{{ $waHelpUrl }}" target="_blank" rel="noopener noreferrer" class="font-bold underline hover:text-emerald-950">hubungi Admin via WhatsApp ({{ $adminWa }})</a>.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Official Footer -->
    <footer class="relative z-10 w-full border-t border-white/10 bg-slate-950/80 py-6 text-center text-xs text-slate-400">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p>&copy; {{ date('Y') }} {{ $siteName }}. Terdaftar Resmi Yayasan DonasiFlow Nusantara Semarang.</p>
            <div class="flex items-center gap-4">
                @if($adminEmail)
                    <a href="mailto:{{ $adminEmail }}" class="hover:text-emerald-400 transition">{{ $adminEmail }}</a>
                @endif
                @if($waHelpUrl)
                    <a href="{{ $waHelpUrl }}" target="_blank" rel="noopener" class="text-emerald-400 hover:underline">WhatsApp Resmi</a>
                @endif
            </div>
        </div>
    </footer>

</body>
</html>
