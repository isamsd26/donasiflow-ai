@extends('layouts.auth')

@section('title', 'Masuk Admin')

@section('content')
    <section class="grid w-full max-w-4xl overflow-hidden rounded-xl border border-line bg-surface lg:grid-cols-[0.9fr_1.1fr]">
        <div class="flex flex-col justify-between bg-brand-deep p-8 text-white sm:p-10">
            <div>
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/15 font-display text-lg font-semibold text-white">
                        {{ strtoupper(substr($admin['brand'] ?? 'D', 0, 1)) }}
                    </span>
                    <span class="font-display text-xl font-semibold">{{ $admin['brand'] ?? 'DonasiFlow' }}</span>
                </div>

                <h1 class="mt-8 font-display text-3xl font-semibold leading-tight sm:text-4xl">
                    Ruang kelola penghimpunan dan laporan keuangan.
                </h1>
                <p class="mt-4 text-sm leading-relaxed text-white/80">
                    Kelola program donasi, verifikasi mutasi transfer, dan susun ringkasan eksekutif dari satu panel terpadu.
                </p>
            </div>

            <div class="mt-10 border-t border-white/15 pt-5 text-xs text-white/70">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-white hover:underline">
                    &larr; Kembali ke situs publik
                </a>
            </div>
        </div>

        <div class="p-8 sm:p-10">
            <div>
                <p class="text-xs font-medium text-brand">Akses pengelola</p>
                <h2 class="mt-1.5 font-display text-2xl font-semibold text-ink sm:text-3xl">Masuk ke dashboard</h2>
                <p class="mt-2 text-xs leading-6 text-muted">
                    Gunakan email dan kata sandi akun admin yang terdaftar.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.login.store') }}" class="mt-7 space-y-4">
                @csrf

                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-ink">Email</span>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full rounded-lg border border-line-strong bg-canvas px-3.5 py-2.5 text-sm text-ink outline-none transition-colors focus:border-brand focus:bg-surface"
                        required
                        autofocus
                    >
                    @error('email') <span class="mt-1.5 block text-xs text-danger">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-ink">Kata sandi</span>
                    <input
                        type="password"
                        name="password"
                        class="w-full rounded-lg border border-line-strong bg-canvas px-3.5 py-2.5 text-sm text-ink outline-none transition-colors focus:border-brand focus:bg-surface"
                        required
                    >
                    @error('password') <span class="mt-1.5 block text-xs text-danger">{{ $message }}</span> @enderror
                </label>

                <label class="flex cursor-pointer items-center gap-2.5 pt-1 text-xs text-muted">
                    <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-line-strong text-brand focus:ring-brand">
                    Ingat saya di perangkat ini
                </label>

                <button
                    type="submit"
                    class="mt-2 w-full rounded-lg bg-brand px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-deep"
                >
                    Masuk ke panel admin
                </button>
            </form>
        </div>
    </section>
@endsection
