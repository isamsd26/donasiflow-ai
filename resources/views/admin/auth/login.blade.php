@extends('layouts.auth')

@section('title', 'Login Admin')

@section('content')
    <section class="grid w-full max-w-5xl overflow-hidden rounded-[32px] bg-white shadow-[0_20px_50px_rgba(15,23,42,0.12)] lg:grid-cols-[0.95fr_1.05fr]">
        <div class="bg-[linear-gradient(145deg,#1e293b,#059669)] px-8 py-10 text-white sm:px-10 lg:px-12">
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-100">CMS Login</p>
            <h1 class="mt-6 font-display text-5xl leading-none">{{ $admin['brand'] ?? 'DonasiFlow Admin' }}</h1>
            <p class="mt-6 max-w-md text-sm leading-7 text-emerald-50/90">
                Masuk ke panel administrasi untuk mengelola campaign, banner hero, halaman statis, dan pengaturan situs.
            </p>

            <div class="mt-10 grid gap-4 sm:grid-cols-2">
                <div class="rounded-[24px] border border-white/10 bg-white/10 px-5 py-5">
                    <p class="text-[10px] uppercase tracking-[0.18em] text-emerald-100">Konten</p>
                    <p class="mt-3 font-display text-3xl leading-none">CMS</p>
                    <p class="mt-3 text-sm text-emerald-50/85">Campaign, update, banner, dan halaman publik.</p>
                </div>
                <div class="rounded-[24px] border border-white/10 bg-white/10 px-5 py-5">
                    <p class="text-[10px] uppercase tracking-[0.18em] text-emerald-100">Akses</p>
                    <p class="mt-3 font-display text-3xl leading-none">Aman</p>
                    <p class="mt-3 text-sm text-emerald-50/85">Login dulu sebelum masuk ke area administrasi.</p>
                </div>
            </div>
        </div>

        <div class="px-8 py-10 sm:px-10 lg:px-12">
            <div class="max-w-md">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Admin Access</p>
                <h2 class="mt-2 font-display text-4xl leading-none text-[#1e293b]">Masuk ke dashboard</h2>
                <p class="mt-4 text-sm leading-7 text-[#64748b]">
                    Gunakan akun admin yang sudah terdaftar di database. Untuk environment production, akun awal sebaiknya dibuat melalui variabel environment yang aman atau proses provisioning terpisah.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.login.store') }}" class="mt-8 space-y-5">
                @csrf

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none transition focus:border-pm-blue" required autofocus>
                    @error('email') <span class="mt-2 block text-xs text-[#b42318]">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Password</span>
                    <input type="password" name="password" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none transition focus:border-pm-blue" required>
                    @error('password') <span class="mt-2 block text-xs text-[#b42318]">{{ $message }}</span> @enderror
                </label>

                <label class="flex items-center gap-3 text-sm text-[#33506f]">
                    <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-[#b8c8d8] text-pm-blue focus:ring-pm-blue">
                    Ingat saya di perangkat ini
                </label>

                <button type="submit" class="w-full rounded-full bg-pm-blue px-5 py-3 text-sm font-semibold text-white">
                    Masuk ke Admin
                </button>
            </form>
        </div>
    </section>
@endsection
