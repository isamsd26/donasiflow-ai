@extends('layouts.public')

@section('content')
    <section class="border-b border-line bg-canvas py-10 sm:py-12">
        <div class="mx-auto w-full max-w-5xl px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-medium text-brand">Pelacakan publik</p>
            <h1 class="mt-2 font-display text-3xl font-semibold text-ink sm:text-4xl">Cek status donasi</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-muted">
                Masukkan nomor referensi donasi tanpa perlu login. Data pribadi donatur tetap dijaga dan tidak ditampilkan di halaman ini.
            </p>
        </div>
    </section>

    <section class="bg-surface py-10 sm:py-12">
        <div class="mx-auto grid w-full max-w-5xl gap-8 px-4 sm:px-6 lg:grid-cols-[0.95fr_1.05fr] lg:items-start lg:px-8">
            <div class="rounded-xl border border-line bg-canvas p-6">
                <form method="GET" action="{{ route('donation.lookup') }}" class="space-y-4">
                    <label class="block">
                        <span class="mb-1.5 block text-xs font-semibold text-ink">Nomor referensi</span>
                        <input
                            type="text"
                            name="reference"
                            value="{{ $reference }}"
                            class="w-full rounded-lg border border-line-strong bg-surface px-3.5 py-2.5 font-mono text-sm uppercase text-ink outline-none focus:border-brand"
                            placeholder="INV-20260324-4084"
                        >
                    </label>

                    <div class="flex flex-wrap gap-2.5">
                        <button type="submit" class="rounded-lg bg-brand px-5 py-2.5 text-xs font-semibold text-white transition-colors hover:bg-brand-deep">
                            Cek status
                        </button>
                        @if ($reference !== '')
                            <a href="{{ route('donation.lookup') }}" class="rounded-lg border border-line-strong bg-surface px-4 py-2.5 text-xs font-medium text-ink hover:bg-surface-subtle">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>

                <p class="mt-5 border-t border-line pt-4 text-xs leading-6 text-muted">
                    Simpan nomor referensi atau tautan status setelah mengirim formulir donasi agar Anda bisa memantau verifikasi dan mengunduh invoice PDF kapan saja.
                </p>
            </div>

            <div class="rounded-xl border border-line bg-surface p-6">
                <h2 class="text-sm font-semibold text-ink">Hasil pencarian</h2>

                @if ($reference === '')
                    <div class="mt-4 rounded-lg border border-dashed border-line-strong bg-canvas p-5 text-xs leading-6 text-muted">
                        Masukkan nomor referensi di samping untuk melihat status verifikasi donasi Anda.
                    </div>
                @elseif (! $result)
                    <div class="mt-4 rounded-lg border border-danger/25 bg-danger-soft p-5 text-xs leading-6 text-danger">
                        Nomor referensi <strong>{{ $reference }}</strong> tidak ditemukan. Periksa kembali penulisan kodenya.
                    </div>
                @else
                    @php
                        $statusMeta = [
                            'pending' => ['label' => 'Menunggu transfer', 'class' => 'bg-ochre-soft text-ochre-ink'],
                            'paid' => ['label' => 'Donasi diterima', 'class' => 'bg-brand-soft text-brand'],
                            'failed' => ['label' => 'Verifikasi gagal', 'class' => 'bg-danger-soft text-danger'],
                            'expired' => ['label' => 'Kedaluwarsa', 'class' => 'bg-surface-subtle text-muted'],
                            'refunded' => ['label' => 'Dikembalikan', 'class' => 'bg-surface-subtle text-ink'],
                        ][$result->status] ?? ['label' => str($result->status)->headline(), 'class' => 'bg-surface-subtle text-ink'];
                    @endphp
                    <dl class="mt-4 divide-y divide-line text-sm tabular-nums">
                        <div class="flex items-center justify-between gap-4 py-3">
                            <dt class="text-xs text-muted">Referensi</dt>
                            <dd class="font-mono text-xs font-semibold text-ink">{{ $result->reference }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 py-3">
                            <dt class="text-xs text-muted">Program</dt>
                            <dd class="text-right font-semibold text-ink">{{ $result->campaign?->title ?? '-' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 py-3">
                            <dt class="text-xs text-muted">Total transfer</dt>
                            <dd class="font-semibold text-brand">Rp {{ number_format((float) $result->amount, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 py-3">
                            <dt class="text-xs text-muted">Status</dt>
                            <dd>
                                <span class="inline-flex rounded-md px-2.5 py-1 text-xs font-semibold {{ $statusMeta['class'] }}">
                                    {{ $statusMeta['label'] }}
                                </span>
                            </dd>
                        </div>
                    </dl>

                    <div class="mt-5 border-t border-line pt-4">
                        <a
                            href="{{ route('donation.show', $result->reference) }}"
                            class="inline-flex items-center rounded-lg bg-brand px-4 py-2 text-xs font-semibold text-white transition-colors hover:bg-brand-deep"
                        >
                            Buka detail &amp; instruksi lengkap
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
