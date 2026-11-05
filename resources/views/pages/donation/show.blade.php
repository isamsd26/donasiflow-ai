@extends('layouts.public')

@section('content')
    @php
        $statusMeta = [
            'pending' => ['label' => 'Menunggu Transfer', 'class' => 'bg-[#fff4da] text-[#9a6700]'],
            'paid' => ['label' => 'Donasi Diterima', 'class' => 'bg-[#e8f8ef] text-[#18794e]'],
            'failed' => ['label' => 'Verifikasi Gagal', 'class' => 'bg-[#fff1f1] text-[#b42318]'],
            'expired' => ['label' => 'Kedaluwarsa', 'class' => 'bg-[#eef2f6] text-[#64748b]'],
            'refunded' => ['label' => 'Dikembalikan', 'class' => 'bg-[#eef5ff] text-pm-blue'],
        ][$donation->status] ?? ['label' => str($donation->status)->headline(), 'class' => 'bg-[#eef5ff] text-pm-blue'];
    @endphp

    <div class="mx-auto w-[min(980px,calc(100%-1rem))] space-y-5">
        <section class="rounded-[24px] bg-[linear-gradient(135deg,#1e293b,#059669)] px-5 py-6 text-white shadow-[0_18px_40px_rgba(15,23,42,0.16)] sm:rounded-[30px] sm:px-8 sm:py-8">
            <p class="text-[11px] uppercase tracking-[0.28em] text-emerald-100">Status Donasi</p>
            <h1 class="mt-3 font-display text-[2.45rem] leading-none sm:text-[3.2rem]">{{ $donation->reference }}</h1>
            
            @if ($donation->status === 'pending' && $donation->expires_at)
                <div class="mt-5 rounded-2xl bg-white/10 px-5 py-4 border border-white/20 inline-block">
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-100">Sisa Waktu Transfer</p>
                    <p id="header-countdown" class="mt-1 font-mono text-3xl font-bold tracking-wider text-white">
                        --:--:--
                    </p>
                </div>
            @endif

            <p class="mt-4 max-w-3xl text-sm leading-7 text-emerald-50/90">
                Donasi Anda sudah tercatat. Silakan transfer sesuai total unik agar admin mudah mencocokkan dana masuk di mutasi rekening.
            </p>
            <div class="mt-5 flex flex-wrap gap-3">
                <a
                    href="{{ route('donation.invoice', $donation->reference) }}"
                    class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-pm-blue transition hover:bg-emerald-50"
                >
                    Download Invoice
                </a>
                <button
                    type="button"
                    data-copy-text="{{ route('donation.show', $donation->reference) }}"
                    class="rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/15"
                >
                    Salin Link Status
                </button>
            </div>
            <p class="mt-3 text-xs text-emerald-100/90">Simpan link status atau unduh invoice agar Anda bisa mengecek status donasi lagi tanpa login.</p>
        </section>

        <section class="grid gap-5 lg:grid-cols-[1.05fr_0.95fr]">
            <div class="rounded-[24px] bg-white px-4 py-5 shadow-[0_16px_34px_rgba(15,23,42,0.08)] sm:rounded-[28px] sm:px-5 sm:py-6">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Ringkasan Donasi</p>
                <div class="mt-4 grid gap-3">
                    <div class="rounded-2xl border border-[#e2e8f0] px-4 py-3">
                        <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Campaign</p>
                        <p class="mt-2 text-lg font-semibold text-[#1e293b]">{{ $donation->campaign?->title ?? '-' }}</p>
                    </div>
                    <div class="rounded-2xl border border-[#e2e8f0] px-4 py-3">
                        <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Nominal Donasi</p>
                        <p class="mt-2 text-lg font-semibold text-[#1e293b]">Rp {{ number_format((float) ($donation->base_amount ?: $donation->amount), 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl border border-[#e2e8f0] px-4 py-3">
                        <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Kode Unik Verifikasi</p>
                        <p class="mt-2 text-lg font-semibold text-[#1e293b]">Rp {{ number_format((int) $donation->unique_code, 0, ',', '.') }}</p>
                        <p class="mt-2 text-xs leading-5 text-[#64748b]">Tambahan angka ini masuk sebagai bagian dari donasi dan membantu admin menemukan transfer Anda di mutasi rekening.</p>
                    </div>
                    <div class="rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] px-4 py-3">
                        <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Total Yang Harus Ditransfer</p>
                        <p class="mt-2 text-2xl font-semibold text-[#1e293b]">Rp {{ number_format((float) $donation->amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl border border-[#e2e8f0] px-4 py-3">
                        <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Status</p>
                        <p class="mt-2 inline-flex rounded-full px-3 py-1 text-sm font-semibold {{ $statusMeta['class'] }}">{{ $statusMeta['label'] }}</p>
                    </div>
                    <div class="rounded-2xl border border-[#e2e8f0] px-4 py-3">
                        <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Dibuat</p>
                        <p class="mt-2 text-lg font-semibold text-[#1e293b]">{{ optional($donation->created_at)->translatedFormat('d F Y, H:i') }}</p>
                    </div>
                    <div class="rounded-2xl border border-[#e2e8f0] px-4 py-3">
                        <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Batas Status Pending</p>
                        <p class="mt-2 text-lg font-semibold text-[#1e293b]">
                            {{ $donation->expires_at ? $donation->expires_at->translatedFormat('d F Y, H:i') : 'Belum ditentukan' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                @if ($transaction && $transaction->gateway === 'midtrans')
                    <div class="rounded-[24px] border border-[#e2e8f0] bg-[#f8fafc] px-4 py-5 shadow-[0_16px_34px_rgba(15,23,42,0.05)] sm:rounded-[28px] sm:px-5 sm:py-6">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Pembayaran Midtrans</p>
                        <div class="mt-4 text-sm leading-7 text-[#334155]">
                            @if ($donation->status === 'pending')
                                <p>Silakan klik tombol di bawah ini untuk memunculkan popup pembayaran dan memilih metode yang Anda inginkan (QRIS, Virtual Account, GoPay, dll).</p>
                                <div class="mt-5">
                                    <button type="button" id="pay-button" class="inline-flex rounded-full bg-pm-blue px-6 py-3 text-sm font-semibold text-white transition hover:bg-pm-blue/90">
                                        Bayar Sekarang
                                    </button>
                                </div>
                            @elseif ($donation->status === 'paid')
                                <p>Pembayaran otomatis via Midtrans telah berhasil diverifikasi.</p>
                            @else
                                <p>Pembayaran Midtrans tidak dapat dilanjutkan karena status transaksi <strong>{{ $statusMeta['label'] }}</strong>.</p>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="rounded-[24px] border border-[#e2e8f0] bg-[#f8fafc] px-4 py-5 shadow-[0_16px_34px_rgba(15,23,42,0.05)] sm:rounded-[28px] sm:px-5 sm:py-6">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Rekening Tujuan</p>
                        <div class="mt-4 grid gap-3">
                            <div class="rounded-2xl border border-[#e2e8f0] bg-white px-4 py-3">
                                <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Bank</p>
                                <p class="mt-2 text-lg font-semibold text-[#1e293b]">{{ $donation->payment_bank ?: 'Bank Syariah Nasional' }}</p>
                            </div>
                            <div class="rounded-2xl border border-[#e2e8f0] bg-white px-4 py-3">
                                <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Nomor Rekening</p>
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    <p class="font-mono text-lg font-semibold text-[#1e293b]">{{ $donation->payment_account_number ?: '-' }}</p>
                                    @if ($donation->payment_account_number)
                                        <button type="button" data-copy-text="{{ $donation->payment_account_number }}" class="rounded-full border border-[#e2e8f0] px-3 py-1 text-xs font-semibold text-[#334155]">Salin</button>
                                    @endif
                                </div>
                            </div>
                            <div class="rounded-2xl border border-[#e2e8f0] bg-white px-4 py-3">
                                <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Atas Nama</p>
                                <p class="mt-2 text-lg font-semibold text-[#1e293b]">{{ $donation->payment_account_name ?: '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[24px] bg-white px-4 py-5 shadow-[0_16px_34px_rgba(15,23,42,0.08)] sm:rounded-[28px] sm:px-5 sm:py-6">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Setelah Transfer</p>
                        <div class="mt-4 space-y-3 text-sm leading-7 text-[#334155]">
                            @if ($donation->status === 'paid')
                                <p><strong>Terima kasih.</strong> Donasi Anda sudah diterima dan diverifikasi admin.</p>
                                <p>Waktu verifikasi: {{ $donation->verified_at ? $donation->verified_at->translatedFormat('d F Y, H:i') : optional($donation->paid_at)->translatedFormat('d F Y, H:i') }}.</p>
                            @elseif ($donation->status === 'expired')
                                <p>Donasi ini sudah melewati batas transfer. Silakan buat donasi baru jika masih ingin mendukung campaign ini.</p>
                            @elseif ($donation->status === 'failed')
                                <p>Verifikasi donasi ini belum berhasil. Silakan hubungi admin dengan nomor referensi di atas.</p>
                            @else
                                <p>Transfer tepat sebesar <strong>Rp {{ number_format((float) $donation->amount, 0, ',', '.') }}</strong> sebelum batas waktu pending.</p>
                                <p>Setelah transfer, kirim konfirmasi singkat ke WhatsApp admin. Admin akan mengecek mutasi rekening dan mengubah status menjadi <strong>Donasi Diterima</strong> setelah dana masuk.</p>
                                @if ($whatsAppUrl)
                                    <div id="wa-action-container">
                                        <a href="{{ $whatsAppUrl }}" id="wa-confirm-btn" target="_blank" rel="noopener" class="inline-flex rounded-full bg-[#18794e] px-4 py-2 text-sm font-semibold text-white transition">Konfirmasi via WhatsApp</a>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif

                <div class="rounded-[24px] bg-[linear-gradient(135deg,#1e293b,#059669)] px-4 py-5 text-white shadow-[0_16px_34px_rgba(15,23,42,0.12)] sm:rounded-[28px] sm:px-5 sm:py-6">
                    <p class="text-[10px] uppercase tracking-[0.18em] text-emerald-100">Aksi Berikutnya</p>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <a href="{{ route('campaign.show', $donation->campaign?->slug ?? '') }}" class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-pm-blue">Kembali ke Campaign</a>
                        <a href="{{ route('donation.lookup', ['reference' => $donation->reference]) }}" class="rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-semibold text-white">Buka via Cek Status</a>
                        <a href="{{ route('campaign.index') }}" class="rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-semibold text-white">Lihat Campaign Lain</a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @if ($donation->status === 'pending' && $donation->expires_at)
        <!-- Floating Sticky Bar -->
        <div id="sticky-countdown-bar" class="fixed bottom-0 left-0 right-0 z-50 translate-y-full border-t border-red-200 bg-red-50 px-4 py-3 shadow-[0_-10px_30px_rgba(180,35,24,0.1)] transition-transform duration-500 sm:px-6">
            <div class="mx-auto flex max-w-3xl flex-col items-center justify-between gap-2 sm:flex-row">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-red-900">Segera Selesaikan Pembayaran</p>
                        <p class="text-xs text-red-700">Waktu Anda terbatas untuk donasi ini.</p>
                    </div>
                </div>
                <div class="font-mono text-xl font-bold tracking-wider text-red-600" id="floating-countdown">
                    --:--:--
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const expiresAt = new Date(@json($donation->expires_at->toIso8601String())).getTime();
                const headerCd = document.getElementById('header-countdown');
                const floatingCd = document.getElementById('floating-countdown');
                const stickyBar = document.getElementById('sticky-countdown-bar');
                const waBtn = document.getElementById('wa-confirm-btn');
                const waContainer = document.getElementById('wa-action-container');

                if (stickyBar) {
                    setTimeout(() => stickyBar.classList.remove('translate-y-full'), 500);
                }

                function updateCountdown() {
                    const now = new Date().getTime();
                    const distance = expiresAt - now;

                    if (distance < 0) {
                        clearInterval(interval);
                        const expiredText = 'WAKTU HABIS';
                        if (headerCd) headerCd.textContent = expiredText;
                        if (floatingCd) floatingCd.textContent = expiredText;
                        if (waBtn) {
                            waBtn.classList.add('opacity-50', 'pointer-events-none', 'bg-gray-400');
                            waBtn.classList.remove('bg-[#18794e]');
                            waBtn.textContent = 'Waktu Habis';
                            if (waContainer && !waContainer.querySelector('.expired-msg')) {
                                const msg = document.createElement('p');
                                msg.className = 'expired-msg text-xs text-red-600 mt-2 font-semibold';
                                msg.textContent = '*Batas waktu transfer telah berlalu. Silakan buat donasi baru.';
                                waContainer.appendChild(msg);
                            }
                        }
                        return;
                    }

                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    const formatted = [
                        String(hours).padStart(2, '0'),
                        String(minutes).padStart(2, '0'),
                        String(seconds).padStart(2, '0')
                    ].join(':');

                    if (headerCd) headerCd.textContent = formatted;
                    if (floatingCd) floatingCd.textContent = formatted;
                }

                updateCountdown();
                const interval = setInterval(updateCountdown, 1000);
            });
        </script>
        @endpush
    @endif

    @if ($transaction && $transaction->gateway === 'midtrans' && $donation->status === 'pending' && isset($transaction->payload['snap_token']))
        @push('scripts')
        <script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const payButton = document.getElementById('pay-button');
                if (payButton) {
                    payButton.addEventListener('click', function () {
                        snap.pay('{{ $transaction->payload['snap_token'] }}', {
                            onSuccess: function (result) {
                                window.location.reload();
                            },
                            onPending: function (result) {
                                window.location.reload();
                            },
                            onError: function (result) {
                                alert("Pembayaran gagal!");
                            },
                            onClose: function () {
                                // User closed the popup
                            }
                        });
                    });
                    
                    // Auto open on load if session has success
                    @if(session('success'))
                        payButton.click();
                    @endif
                }
            });
        </script>
        @endpush
    @endif
@endsection
                                                                                                                                     