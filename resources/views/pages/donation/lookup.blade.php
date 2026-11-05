@extends('layouts.public')

@section('content')
    <div class="mx-auto w-[min(980px,calc(100%-1rem))] space-y-5">
        <section class="rounded-[24px] bg-[linear-gradient(135deg,#1e293b,#059669)] px-5 py-6 text-white shadow-[0_18px_40px_rgba(15,23,42,0.16)] sm:rounded-[30px] sm:px-8 sm:py-8">
            <p class="text-[11px] uppercase tracking-[0.28em] text-emerald-100">Cek Status Donasi</p>
            <h1 class="mt-3 font-display text-[2.45rem] leading-none sm:text-[3.2rem]">Masukkan nomor referensi</h1>
            <p class="mt-4 max-w-3xl text-sm leading-7 text-emerald-50/90">
                Halaman ini bisa dipakai tanpa login. Cukup masukkan nomor referensi donasi, lalu sistem akan menampilkan status dengan informasi yang aman dan minimal.
            </p>
        </section>

        <section class="grid gap-5 lg:grid-cols-[1.05fr_0.95fr]">
            <div class="rounded-[24px] bg-white px-4 py-5 shadow-[0_16px_34px_rgba(15,23,42,0.08)] sm:rounded-[28px] sm:px-5 sm:py-6">
                <form method="GET" action="{{ route('donation.lookup') }}" class="space-y-4">
                    <label class="block">
                        <span class="mb-2 block text-sm font-medium text-[#334155]">Nomor Referensi</span>
                        <input
                            type="text"
                            name="reference"
                            value="{{ $reference }}"
                            class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm uppercase outline-none focus:border-pm-blue"
                            placeholder="Contoh: INV-20260324-4084"
                        >
                    </label>

                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="rounded-full bg-pm-blue px-5 py-3 text-sm font-semibold text-white">Cek Status</button>
                        @if ($reference !== '')
                            <a href="{{ route('donation.lookup') }}" class="rounded-full border border-[#e2e8f0] px-5 py-3 text-sm font-medium text-[#334155]">Reset</a>
                        @endif
                    </div>
                </form>

                <div class="mt-5 rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] px-4 py-4 text-sm leading-7 text-[#334155]">
                    <p class="font-semibold text-[#1e293b]">Catatan</p>
                    <p class="mt-2">Simpan nomor referensi atau link status setelah mengirim donasi. Halaman ini sengaja tidak menampilkan data pribadi donatur.</p>
                </div>
            </div>

            <div class="rounded-[24px] bg-white px-4 py-5 shadow-[0_16px_34px_rgba(15,23,42,0.08)] sm:rounded-[28px] sm:px-5 sm:py-6">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Hasil Pencarian</p>

                @if ($reference === '')
                    <div class="mt-4 rounded-2xl border border-dashed border-[#e2e8f0] px-4 py-5 text-sm leading-7 text-[#5f7287]">
                        Masukkan nomor referensi di samping untuk melihat status donasi Anda.
                    </div>
                @elseif (! $result)
                    <div class="mt-4 rounded-2xl border border-[#f2d6d2] bg-[#fff6f5] px-4 py-5 text-sm leading-7 text-[#8a2d24]">
                        Nomor referensi tidak ditemukan. Pastikan kode yang dimasukkan sudah benar.
                    </div>
                @else
                    @php
                        $statusMeta = [
                            'pending' => ['label' => 'Menunggu Transfer', 'class' => 'bg-[#fff4da] text-[#9a6700]'],
                            'paid' => ['label' => 'Donasi Diterima', 'class' => 'bg-[#e8f8ef] text-[#18794e]'],
                            'failed' => ['label' => 'Verifikasi Gagal', 'class' => 'bg-[#fff1f1] text-[#b42318]'],
                            'expired' => ['label' => 'Kedaluwarsa', 'class' => 'bg-[#eef2f6] text-[#64748b]'],
                            'refunded' => ['label' => 'Dikembalikan', 'class' => 'bg-[#eef5ff] text-pm-blue'],
                        ][$result->status] ?? ['label' => str($result->status)->headline(), 'class' => 'bg-[#eef5ff] text-pm-blue'];
                    @endphp
                    <div class="mt-4 grid gap-3">
                        <div class="rounded-2xl border border-[#e2e8f0] px-4 py-3">
                            <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Referensi</p>
                            <p class="mt-2 text-lg font-semibold text-[#1e293b]">{{ $result->reference }}</p>
                        </div>
                        <div class="rounded-2xl border border-[#e2e8f0] px-4 py-3">
                            <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Campaign</p>
                            <p class="mt-2 text-lg font-semibold text-[#1e293b]">{{ $result->campaign?->title ?? '-' }}</p>
                        </div>
                        <div class="rounded-2xl border border-[#e2e8f0] px-4 py-3">
                            <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Total Transfer</p>
                            <p class="mt-2 text-lg font-semibold text-[#1e293b]">Rp {{ number_format((float) $result->amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-2xl border border-[#e2e8f0] px-4 py-3">
                            <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Status</p>
                            <p class="mt-2 inline-flex rounded-full px-3 py-1 text-sm font-semibold {{ $statusMeta['class'] }}">{{ $statusMeta['label'] }}</p>
                        </div>
                        <div class="rounded-2xl border border-[#e2e8f0] px-4 py-3">
                            <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Detail Lengkap</p>
                            <a href="{{ route('donation.show', $result->reference) }}" class="mt-2 inline-flex rounded-full bg-pm-blue px-4 py-2 text-sm font-semibold text-white">Buka Halaman Status</a>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection
