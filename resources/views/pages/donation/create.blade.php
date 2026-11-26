@extends('layouts.public')

@section('content')
    <section class="border-b border-line bg-canvas py-8 sm:py-10">
        <div class="mx-auto w-full max-w-5xl px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-medium text-brand">Checkout donasi</p>
            <h1 class="mt-2 font-display text-3xl font-semibold leading-tight text-ink sm:text-4xl">
                Dukung {{ $campaign->title }}
            </h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-muted">
                Setelah formulir dikirim, sistem akan membuat nomor referensi dan nominal transfer unik untuk mempermudah verifikasi mutasi rekening.
            </p>
        </div>
    </section>

    <section class="bg-surface py-8 sm:py-12">
        <div class="mx-auto grid w-full max-w-5xl gap-8 px-4 sm:px-6 lg:grid-cols-[0.82fr_1.18fr] lg:items-start lg:px-8">
            <aside class="rounded-xl border border-line bg-canvas p-5 sm:p-6">
                <p class="text-xs font-medium text-brand">Ringkasan program</p>
                <h2 class="mt-1.5 font-display text-xl font-semibold text-ink">{{ $campaign->title }}</h2>
                <p class="mt-2.5 text-xs leading-6 text-muted">{{ $campaign->summary }}</p>

                <dl class="mt-5 divide-y divide-line border-t border-line text-xs tabular-nums">
                    <div class="flex items-center justify-between py-3">
                        <dt class="text-muted">Terkumpul</dt>
                        <dd class="font-semibold text-brand">Rp {{ number_format((float) $campaign->current_amount, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <dt class="text-muted">Target</dt>
                        <dd class="font-semibold text-ink">Rp {{ number_format((float) $campaign->target_amount, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <dt class="text-muted">Pengelola</dt>
                        <dd class="font-semibold text-ink">{{ $campaign->organizer_name }}</dd>
                    </div>
                </dl>

                <div class="mt-4 rounded-lg border border-line bg-surface p-3.5 text-xs leading-6 text-muted">
                    <p class="font-semibold text-ink">Sudah pernah berdonasi?</p>
                    <p class="mt-1">Cek status verifikasi kapan saja memakai nomor referensi.</p>
                    <a href="{{ route('donation.lookup') }}" class="mt-2.5 inline-flex font-semibold text-brand hover:underline">
                        Buka halaman cek status
                    </a>
                </div>
            </aside>

            <form method="POST" action="{{ route('donation.store', $campaign->slug) }}" data-dirty-form class="rounded-xl border border-line bg-surface p-5 sm:p-7">
                @csrf

                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <div class="mb-2 flex items-center justify-between">
                            <label for="donation-amount" class="text-xs font-semibold text-ink">Nominal donasi</label>
                            <span class="text-xs text-muted">Min. Rp 1.000</span>
                        </div>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-semibold text-muted">Rp</span>
                            <input
                                id="donation-amount"
                                type="text"
                                name="amount"
                                inputmode="numeric"
                                value="{{ old('amount') }}"
                                data-money-input
                                class="w-full rounded-lg border border-line-strong bg-canvas py-2.5 pl-11 pr-4 text-base font-semibold text-ink tabular-nums outline-none transition-colors focus:border-brand focus:bg-surface"
                                placeholder="100.000"
                                required
                            >
                        </div>
                        <div class="mt-2.5 flex flex-wrap gap-1.5">
                            @foreach([25000, 50000, 100000, 250000, 500000] as $preset)
                                <button
                                    type="button"
                                    data-preset-amount="{{ $preset }}"
                                    class="rounded-md border border-line bg-canvas px-3 py-1.5 text-xs font-medium text-ink-soft tabular-nums transition-colors hover:border-brand hover:bg-brand-soft hover:text-brand"
                                >
                                    Rp {{ number_format($preset, 0, ',', '.') }}
                                </button>
                            @endforeach
                        </div>
                        @error('amount') <span class="mt-1.5 block text-xs font-medium text-danger">{{ $message }}</span> @enderror
                    </div>

                    <label class="block">
                        <span class="mb-1.5 block text-xs font-semibold text-ink">Nama lengkap</span>
                        <input
                            type="text"
                            name="donor_name"
                            value="{{ old('donor_name') }}"
                            class="w-full rounded-lg border border-line-strong bg-canvas px-3.5 py-2.5 text-sm text-ink outline-none transition-colors focus:border-brand focus:bg-surface"
                            placeholder="Nama Anda"
                            required
                        >
                        @error('donor_name') <span class="mt-1.5 block text-xs font-medium text-danger">{{ $message }}</span> @enderror
                    </label>

                    <label class="block">
                        <span class="mb-1.5 block text-xs font-semibold text-ink">Nomor WhatsApp / HP</span>
                        <input
                            type="tel"
                            name="donor_phone"
                            inputmode="tel"
                            value="{{ old('donor_phone') }}"
                            class="w-full rounded-lg border border-line-strong bg-canvas px-3.5 py-2.5 text-sm text-ink outline-none transition-colors focus:border-brand focus:bg-surface"
                            placeholder="081234567890"
                            required
                        >
                        @error('donor_phone') <span class="mt-1.5 block text-xs font-medium text-danger">{{ $message }}</span> @enderror
                    </label>

                    <label class="block sm:col-span-2">
                        <span class="mb-1.5 block text-xs font-semibold text-ink">Email <span class="font-normal text-muted">(opsional)</span></span>
                        <input
                            type="email"
                            name="donor_email"
                            value="{{ old('donor_email') }}"
                            class="w-full rounded-lg border border-line-strong bg-canvas px-3.5 py-2.5 text-sm text-ink outline-none transition-colors focus:border-brand focus:bg-surface"
                            placeholder="email@contoh.com"
                        >
                        @error('donor_email') <span class="mt-1.5 block text-xs font-medium text-danger">{{ $message }}</span> @enderror
                    </label>

                    <label class="block sm:col-span-2">
                        <div class="mb-1.5 flex items-center justify-between">
                            <span class="text-xs font-semibold text-ink">Doa atau pesan kebaikan <span class="font-normal text-muted">(opsional)</span></span>
                            <span class="text-xs text-muted" id="prayer-counter">Maks. 1.000 karakter</span>
                        </div>
                        <textarea
                            name="prayer_message"
                            rows="3"
                            maxlength="1000"
                            class="w-full rounded-lg border border-line-strong bg-canvas px-3.5 py-2.5 text-sm text-ink outline-none transition-colors focus:border-brand focus:bg-surface"
                            placeholder="Tuliskan doa atau pesan untuk penerima manfaat..."
                        >{{ old('prayer_message') }}</textarea>
                        @error('prayer_message') <span class="mt-1.5 block text-xs font-medium text-danger">{{ $message }}</span> @enderror
                    </label>

                    <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-line bg-canvas px-4 py-3 text-xs font-medium text-ink sm:col-span-2">
                        <input type="hidden" name="is_anonymous" value="0">
                        <input type="checkbox" name="is_anonymous" value="1" @checked(old('is_anonymous')) class="h-4 w-4 rounded border-line-strong text-brand focus:ring-brand">
                        Tampilkan sebagai anonim di daftar donatur publik
                    </label>

                    <div class="sm:col-span-2">
                        <span class="mb-2 block text-xs font-semibold text-ink">Metode pembayaran</span>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-line-strong bg-canvas p-3.5 text-xs transition-colors has-[:checked]:border-brand has-[:checked]:bg-brand-soft/40">
                                <input type="radio" name="payment_gateway" value="manual" class="mt-0.5 h-4 w-4 border-line-strong text-brand focus:ring-brand" @checked(old('payment_gateway', 'manual') === 'manual') required>
                                <span>
                                    <span class="block font-semibold text-ink">Transfer manual</span>
                                    <span class="mt-0.5 block text-muted">Transfer bank dengan kode unik verifikasi</span>
                                </span>
                            </label>
                            <label class="flex cursor-not-allowed items-start gap-3 rounded-lg border border-line bg-surface-subtle p-3.5 text-xs opacity-60 select-none" title="Fitur ini belum tersedia">
                                <input type="radio" name="payment_gateway" value="midtrans" class="pointer-events-none mt-0.5 h-4 w-4" disabled>
                                <span>
                                    <span class="block font-semibold text-muted">Bayar otomatis</span>
                                    <span class="mt-0.5 block text-muted">Segera hadir</span>
                                </span>
                            </label>
                        </div>
                        @error('payment_gateway') <span class="mt-1.5 block text-xs text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap items-center justify-end gap-3 border-t border-line pt-5">
                    <a href="{{ route('campaign.show', $campaign->slug) }}" class="rounded-lg border border-line-strong px-4 py-2.5 text-xs font-medium text-ink hover:bg-surface-subtle">
                        Kembali
                    </a>
                    <button type="submit" class="rounded-lg bg-brand px-5 py-2.5 text-xs font-semibold text-white transition-colors hover:bg-brand-deep">
                        Lanjut ke instruksi transfer
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
