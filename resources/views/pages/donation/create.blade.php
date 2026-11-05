@extends('layouts.public')

@section('content')
    <div class="mx-auto w-[min(1120px,calc(100%-1rem))] space-y-5">
        <section class="rounded-[24px] bg-[linear-gradient(135deg,#1e293b,#059669)] px-5 py-6 text-white shadow-[0_18px_40px_rgba(15,23,42,0.16)] sm:rounded-[30px] sm:px-8 sm:py-8">
            <p class="text-[11px] uppercase tracking-[0.28em] text-emerald-100">Checkout Donasi</p>
            <h1 class="mt-3 font-display text-[2.45rem] leading-none sm:text-[3.2rem]">Dukung {{ $campaign->title }}</h1>
            <p class="mt-4 max-w-3xl text-sm leading-7 text-emerald-50/90">
                Setelah form dikirim, sistem akan membuat nomor referensi dan nominal transfer unik agar admin lebih mudah mencocokkan dana masuk di mutasi rekening.
            </p>
        </section>

        <section class="grid gap-5 lg:grid-cols-[0.9fr_1.1fr]">
            <aside class="rounded-[24px] bg-white px-4 py-5 shadow-[0_16px_34px_rgba(15,23,42,0.08)] sm:rounded-[28px] sm:px-5 sm:py-6">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Ringkasan Campaign</p>
                <h2 class="mt-2 font-display text-[2rem] leading-none text-[#1e293b]">{{ $campaign->title }}</h2>
                <p class="mt-4 text-sm leading-7 text-[#334155]">{{ $campaign->summary }}</p>

                <div class="mt-5 grid gap-3">
                    <div class="rounded-2xl border border-[#e2e8f0] px-4 py-3">
                        <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Terkumpul</p>
                        <p class="mt-2 text-lg font-semibold text-[#1e293b]">Rp {{ number_format((float) $campaign->current_amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl border border-[#e2e8f0] px-4 py-3">
                        <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Target</p>
                        <p class="mt-2 text-lg font-semibold text-[#1e293b]">Rp {{ number_format((float) $campaign->target_amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl border border-[#e2e8f0] px-4 py-3">
                        <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Pengelola</p>
                        <p class="mt-2 text-lg font-semibold text-[#1e293b]">{{ $campaign->organizer_name }}</p>
                    </div>
                </div>
            </aside>

            <form method="POST" action="{{ route('donation.store', $campaign->slug) }}" data-dirty-form class="rounded-[24px] bg-white px-4 py-5 shadow-[0_16px_34px_rgba(15,23,42,0.08)] sm:rounded-[28px] sm:px-5 sm:py-6">
                @csrf

                <div class="mb-5 rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] px-4 py-4 text-sm leading-7 text-[#334155]">
                    <p class="font-semibold text-[#1e293b]">Alur transfer rekening</p>
                    <p class="mt-2">Isi data donasi, transfer sesuai total unik yang muncul, lalu kirim konfirmasi sederhana ke WhatsApp admin dari halaman status.</p>
                    <a href="{{ route('donation.lookup') }}" class="mt-3 inline-flex rounded-full bg-pm-blue px-4 py-2 text-xs font-semibold text-white">
                        Cek Status Donasi
                    </a>
                </div>

                <div class="grid gap-5 lg:grid-cols-2">
                    <div class="lg:col-span-2">
                        <div class="flex items-center justify-between mb-2">
                            <span class="block text-sm font-medium text-[#334155]">Nominal Donasi</span>
                            <span class="text-xs text-pm-muted">Min. Rp 1.000</span>
                        </div>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-[#64748b]">Rp</span>
                            <input type="text" name="amount" inputmode="numeric" value="{{ old('amount') }}" data-money-input class="w-full rounded-2xl border border-[#e2e8f0] pl-12 pr-4 py-3 text-base font-semibold outline-none focus:border-pm-blue focus:ring-2 focus:ring-pm-blue/15" placeholder="100.000" required>
                        </div>
                        <div class="mt-2.5 flex flex-wrap gap-2">
                            @foreach([25000, 50000, 100000, 250000, 500000] as $preset)
                                <button type="button" data-preset-amount="{{ $preset }}" class="rounded-xl border border-[#e2e8f0] bg-[#f8fafc] px-3 py-1.5 text-xs font-semibold text-[#334155] transition hover:border-pm-blue hover:bg-emerald-50 hover:text-pm-blue">
                                    Rp {{ number_format($preset, 0, ',', '.') }}
                                </button>
                            @endforeach
                        </div>
                        @error('amount') <span class="mt-2 block text-xs text-[#b42318] font-medium">{{ $message }}</span> @enderror
                    </div>

                    <label class="block">
                        <span class="mb-2 block text-sm font-medium text-[#334155]">Nama Lengkap</span>
                        <input type="text" name="donor_name" value="{{ old('donor_name') }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue focus:ring-2 focus:ring-pm-blue/15" placeholder="Nama Anda" required>
                        @error('donor_name') <span class="mt-2 block text-xs text-[#b42318] font-medium">{{ $message }}</span> @enderror
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-medium text-[#334155]">Nomor WhatsApp / HP</span>
                        <input type="tel" name="donor_phone" inputmode="tel" value="{{ old('donor_phone') }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue focus:ring-2 focus:ring-pm-blue/15" placeholder="Contoh: 081234567890" required>
                        @error('donor_phone') <span class="mt-2 block text-xs text-[#b42318] font-medium">{{ $message }}</span> @enderror
                    </label>

                    <label class="block lg:col-span-2">
                        <span class="mb-2 block text-sm font-medium text-[#334155]">Email <span class="text-xs text-pm-muted font-normal">(Opsional)</span></span>
                        <input type="email" name="donor_email" value="{{ old('donor_email') }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue focus:ring-2 focus:ring-pm-blue/15" placeholder="email@contoh.com">
                        @error('donor_email') <span class="mt-2 block text-xs text-[#b42318] font-medium">{{ $message }}</span> @enderror
                    </label>

                    <label class="block lg:col-span-2">
                        <div class="flex items-center justify-between mb-2">
                            <span class="block text-sm font-medium text-[#334155]">Doa / Pesan Kebaikan <span class="text-xs text-pm-muted font-normal">(Opsional)</span></span>
                            <span class="text-xs text-pm-muted" id="prayer-counter">Maks. 1.000 karakter</span>
                        </div>
                        <textarea name="prayer_message" rows="4" maxlength="1000" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue focus:ring-2 focus:ring-pm-blue/15" placeholder="Tuliskan doa atau pesan kebaikan untuk campaign ini...">{{ old('prayer_message') }}</textarea>
                        @error('prayer_message') <span class="mt-2 block text-xs text-[#b42318] font-medium">{{ $message }}</span> @enderror
                    </label>

                    <label class="flex items-center gap-3 rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm font-medium text-[#334155] lg:col-span-2">
                        <input type="hidden" name="is_anonymous" value="0">
                        <input type="checkbox" name="is_anonymous" value="1" @checked(old('is_anonymous')) class="h-4 w-4 rounded border-[#b8c8d8] text-pm-blue focus:ring-pm-blue">
                        Tampilkan sebagai anonim di halaman publik
                    </label>

                    <div class="lg:col-span-2">
                        <span class="mb-3 block text-sm font-medium text-[#334155]">Metode Pembayaran</span>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-[#e2e8f0] px-4 py-4 text-sm font-medium text-[#334155] has-[:checked]:border-pm-blue has-[:checked]:bg-[#f8fafc] has-[:checked]:text-pm-blue">
                                <input type="radio" name="payment_gateway" value="manual" class="h-4 w-4 border-[#b8c8d8] text-pm-blue focus:ring-pm-blue" @checked(old('payment_gateway', 'manual') === 'manual') required>
                                <span>Transfer Manual<br><span class="text-xs text-pm-muted font-normal">Transfer bank dengan kode unik</span></span>
                            </label>
                            <label class="flex cursor-not-allowed items-center gap-3 rounded-2xl border border-[#e2e8f0] px-4 py-4 text-sm font-medium opacity-50 select-none" title="Fitur ini belum tersedia">
                                <input type="radio" name="payment_gateway" value="midtrans" class="h-4 w-4 border-[#b8c8d8] text-pm-blue focus:ring-pm-blue pointer-events-none" disabled>
                                <span class="text-gray-500">Bayar Otomatis<br><span class="text-xs font-normal">Coming Soon</span></span>
                            </label>
                        </div>
                        @error('payment_gateway') <span class="mt-2 block text-xs text-[#b42318]">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap justify-end gap-3">
                    <a href="{{ route('campaign.show', $campaign->slug) }}" class="rounded-full border border-[#e2e8f0] px-5 py-3 text-sm font-medium text-[#334155]">Kembali</a>
                    <button type="submit" class="rounded-full bg-pm-blue px-5 py-3 text-sm font-semibold text-white">Lanjut ke Instruksi Transfer</button>
                </div>
            </form>
        </section>
    </div>
@endsection
