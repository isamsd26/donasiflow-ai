@extends('layouts.admin')

@section('content')
    <section class="rounded-xl border border-line bg-surface">
        <div class="flex items-center justify-between border-b border-line px-5 py-5">
            <div>
                <a href="{{ route('admin.donations') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-brand hover:underline mb-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar Donasi
                </a>
                <h2 class="mt-1 font-display text-3xl leading-none text-ink">Detail Donasi: {{ $donation->reference }}</h2>
            </div>
            <div class="flex items-center gap-2">
                @if(($donation->metadata['source'] ?? '') === 'admin_manual')
                    <span class="rounded-full bg-amber-100 px-3.5 py-1.5 text-xs font-semibold text-amber-800">Donasi Manual</span>
                @endif
                <span class="rounded-full bg-brand-soft px-4 py-2 text-sm font-semibold text-brand">{{ str($donation->status)->headline() }}</span>
            </div>
        </div>

        <div class="grid gap-6 p-6 lg:grid-cols-2">
            @if(($donation->metadata['source'] ?? '') === 'admin_manual')
                <div class="lg:col-span-2 rounded-lg border border-amber-200 bg-amber-50 p-4 text-xs text-amber-900">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center rounded-full bg-amber-200 p-1 text-amber-900">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </span>
                        <p class="text-sm font-bold text-amber-900">Donasi Diinput Manual oleh Admin</p>
                    </div>
                    <div class="mt-2 text-xs text-[#78350f] grid sm:grid-cols-2 gap-2">
                        <p>Metode Pembayaran: <strong>{{ $donation->metadata['payment_method_label'] ?? 'Manual Admin' }}</strong></p>
                        @if(!empty($donation->metadata['created_by_admin_name']))
                            <p>Petugas Admin: <strong>{{ $donation->metadata['created_by_admin_name'] }}</strong></p>
                        @endif
                    </div>
                </div>
            @endif
            <div class="space-y-6">
                <!-- Informasi Donatur -->
                <div class="rounded-lg border border-line bg-canvas p-5">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-brand mb-4">Informasi Donatur</p>
                    <div class="space-y-4 text-sm text-ink">
                        <div>
                            <p class="text-xs text-muted">Nama</p>
                            <p class="font-semibold">{{ $donation->donor_name }} @if($donation->is_anonymous) <span class="ml-2 text-xs text-muted">(Tampil sebagai Anonim)</span> @endif</p>
                        </div>
                        <div>
                            <p class="text-xs text-muted">Email</p>
                            <p class="font-semibold">{{ $donation->donor_email ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-muted">No. HP / WhatsApp</p>
                            <p class="font-semibold">{{ $donation->donor_phone ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-muted">Doa & Pesan</p>
                            @if($donation->prayer_message)
                                <div class="mt-1 rounded-xl bg-white p-3 border border-line italic text-ink-soft">
                                    "{{ $donation->prayer_message }}"
                                </div>
                            @else
                                <p class="font-semibold">-</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Informasi Campaign -->
                <div class="rounded-lg border border-line bg-canvas p-5">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-brand mb-4">Tujuan Donasi</p>
                    <div class="space-y-4 text-sm text-ink">
                        <div>
                            <p class="text-xs text-muted">Campaign</p>
                            <p class="font-semibold">{{ $donation->campaign?->title ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Rincian Nominal -->
                <div class="rounded-lg border border-line bg-white shadow-sm p-5">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-brand mb-4">Rincian Nominal</p>
                    <div class="space-y-3 text-sm text-ink">
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-muted">Nilai Donasi</span>
                            <span class="font-semibold">Rp {{ number_format((float) ($donation->base_amount ?: $donation->amount), 0, ',', '.') }}</span>
                        </div>
                        @if((int) $donation->unique_code > 0)
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-muted">Kode Unik</span>
                            <span class="font-semibold">Rp {{ number_format((int) $donation->unique_code, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between pt-1">
                            <span class="font-bold">Total Transfer</span>
                            <span class="text-lg font-bold text-brand">Rp {{ number_format((float) $donation->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Informasi Transfer -->
                <div class="rounded-lg border border-line bg-canvas p-5">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-brand mb-4">Waktu & Verifikasi</p>
                    <div class="space-y-4 text-sm text-ink">
                        <div>
                            <p class="text-xs text-muted">Waktu Dibuat</p>
                            <p class="font-semibold">{{ $donation->created_at->translatedFormat('d F Y, H:i:s') }}</p>
                        </div>
                        @if($donation->expires_at)
                        <div>
                            <p class="text-xs text-muted">Batas Waktu Pending</p>
                            <p class="font-semibold">{{ $donation->expires_at->translatedFormat('d F Y, H:i:s') }}</p>
                        </div>
                        @endif
                        @if($donation->paid_at)
                        <div>
                            <p class="text-xs text-muted">Waktu Lunas (Paid)</p>
                            <p class="font-semibold text-[#18794e]">{{ $donation->paid_at->translatedFormat('d F Y, H:i:s') }}</p>
                        </div>
                        @endif
                        @if($donation->admin_note)
                        <div>
                            <p class="text-xs text-muted">Catatan Admin</p>
                            <p class="font-semibold">{{ $donation->admin_note }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                

            </div>
        </div>
    </section>


@endsection
