@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        {{-- Hairline KPI Strip --}}
        <section class="grid divide-y divide-line overflow-hidden rounded-xl border border-line bg-surface sm:grid-cols-2 sm:divide-y-0 lg:grid-cols-4 lg:divide-x tabular-nums">
            @foreach ($dashboard['stats'] as $stat)
                <article class="p-5">
                    <p class="text-xs font-medium text-muted">{{ $stat['label'] }}</p>
                    <p class="mt-2 font-display text-3xl font-semibold leading-none text-ink">{{ $stat['value'] }}</p>
                    <p class="mt-2 text-xs leading-5 text-muted">{{ $stat['note'] }}</p>
                </article>
            @endforeach
        </section>

        {{-- Main Grid: Recent Transactions + Side Column --}}
        <section class="grid gap-6 xl:grid-cols-[1.25fr_0.75fr] xl:items-start">
            <div class="overflow-hidden rounded-xl border border-line bg-surface">
                <div class="flex items-center justify-between gap-4 border-b border-line px-5 py-4">
                    <div>
                        <h2 class="font-display text-xl font-semibold text-ink">Transaksi terbaru</h2>
                        <p class="mt-0.5 text-xs text-muted">Ringkasan aktivitas donasi masuk terakhir.</p>
                    </div>
                    <a
                        href="{{ route('admin.donations') }}"
                        class="rounded-lg border border-line-strong bg-canvas px-3 py-1.5 text-xs font-medium text-ink transition-colors hover:bg-surface-subtle"
                    >
                        Lihat semua
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-xs sm:text-sm tabular-nums">
                        <thead>
                            <tr class="border-b border-line bg-canvas text-xs text-muted">
                                <th class="px-5 py-3 font-medium">Referensi</th>
                                <th class="px-5 py-3 font-medium">Program</th>
                                <th class="px-5 py-3 font-medium">Donatur</th>
                                <th class="px-5 py-3 font-medium text-right">Nominal</th>
                                <th class="px-5 py-3 font-medium text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @forelse ($dashboard['recent_transactions'] as $row)
                                <tr class="transition-colors hover:bg-canvas/70">
                                    <td class="px-5 py-3.5 font-mono text-xs text-ink">
                                        <a href="{{ route('admin.donations.show', $row) }}" class="font-medium text-brand hover:underline">
                                            {{ $row->reference }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-3.5 text-ink">{{ $row->campaign?->title ?? '-' }}</td>
                                    <td class="px-5 py-3.5 text-muted">{{ $row->is_anonymous ? 'Anonim' : $row->donor_name }}</td>
                                    <td class="px-5 py-3.5 text-right font-semibold text-ink">Rp {{ number_format((float) $row->amount, 0, ',', '.') }}</td>
                                    <td class="px-5 py-3.5 text-right">
                                        <span class="inline-flex rounded-md px-2.5 py-0.5 text-xs font-medium {{ $row->status === 'paid' ? 'bg-brand-soft text-brand' : 'bg-ochre-soft text-ochre-ink' }}">
                                            {{ str($row->status)->headline() }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-xs text-muted">Belum ada transaksi untuk ditampilkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-6">
                {{-- Ending Campaigns --}}
                <div class="overflow-hidden rounded-xl border border-line bg-surface">
                    <div class="border-b border-line px-5 py-4">
                        <h2 class="font-display text-lg font-semibold text-ink">Campaign hampir berakhir</h2>
                        <p class="mt-0.5 text-xs text-muted">Program yang perlu dipantau menjelang tenggat.</p>
                    </div>
                    <div class="divide-y divide-line">
                        @forelse ($dashboard['ending_campaigns'] as $campaign)
                            <div class="flex items-start justify-between gap-3 px-5 py-3.5">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-medium text-ink">{{ $campaign['title'] }}</p>
                                    <p class="mt-0.5 text-xs text-muted">{{ $campaign['days_left'] }}</p>
                                </div>
                                <span class="shrink-0 rounded-md bg-ochre-soft px-2 py-0.5 text-xs font-semibold text-ochre-ink tabular-nums">
                                    {{ $campaign['progress'] }}
                                </span>
                            </div>
                        @empty
                            <div class="px-5 py-6 text-xs text-muted">
                                Belum ada campaign dengan tanggal selesai yang tercatat.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Quiet Executive Financial Brief Entry (Deep Spruce anchor, no gradient/sparkle) --}}
                <div class="rounded-xl border border-[#0D2B27] bg-[#0D2B27] p-5 text-white">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-medium text-[#E8C89B]">Ringkasan eksekutif</p>
                            <h3 class="mt-1 font-display text-xl font-semibold text-white">Analis Laporan Keuangan</h3>
                        </div>
                        <span class="rounded-md border border-white/15 bg-white/8 px-2 py-0.5 font-mono text-[11px] text-[#C8D6D2]">Brief</span>
                    </div>
                    <p class="mt-2.5 text-xs leading-6 text-[#B9CCC7]">
                        Bedah arus kas, deteksi kampanye kritis, dan evaluasi retensi donatur langsung dari agregat data transaksi terbaru.
                    </p>
                    <div class="mt-4">
                        <a
                            href="{{ route('admin.donations', ['open_ai' => 1]) }}"
                            class="inline-flex w-full items-center justify-center rounded-lg bg-ochre px-4 py-2.5 text-xs font-semibold text-white transition-colors hover:bg-[#AE6D22] sm:w-auto"
                        >
                            Buka analis laporan
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
