@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($dashboard['stats'] as $stat)
                <article class="rounded-[24px] bg-white px-5 py-5 shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">{{ $stat['label'] }}</p>
                    <p class="mt-3 font-display text-4xl leading-none text-[#1e293b]">{{ $stat['value'] }}</p>
                    <p class="mt-3 text-sm leading-6 text-[#64748b]">{{ $stat['note'] }}</p>
                </article>
            @endforeach
        </section>

        <section class="grid gap-5 xl:grid-cols-[1.15fr_0.85fr]">
            <div class="rounded-[26px] bg-white shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
                <div class="border-b border-[#e2e8f0] px-5 py-5">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Transaksi Terbaru</p>
                    <h2 class="mt-1 font-display text-3xl leading-none text-[#1e293b]">Ringkasan aktivitas donasi terbaru.</h2>
                </div>

                <div class="overflow-x-auto px-5 py-5">
                    <table class="min-w-full text-left text-sm">
                        <thead class="text-[#64748b]">
                            <tr class="border-b border-[#e9eff6]">
                                <th class="pb-3 font-semibold">Reference</th>
                                <th class="pb-3 font-semibold">Campaign</th>
                                <th class="pb-3 font-semibold">Donor</th>
                                <th class="pb-3 font-semibold">Nominal</th>
                                <th class="pb-3 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dashboard['recent_transactions'] as $row)
                                <tr class="border-b border-[#f1f5f9] last:border-b-0">
                                    <td class="py-3 text-[#1e293b]">{{ $row->reference }}</td>
                                    <td class="py-3 text-[#1e293b]">{{ $row->campaign?->title ?? '-' }}</td>
                                    <td class="py-3 text-[#64748b]">{{ $row->is_anonymous ? 'Anonim' : $row->donor_name }}</td>
                                    <td class="py-3 font-semibold text-pm-blue">Rp {{ number_format((float) $row->amount, 0, ',', '.') }}</td>
                                    <td class="py-3">
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $row->status === 'paid' ? 'bg-[#dff4e8] text-[#18794e]' : 'bg-[#fff4da] text-[#9a6700]' }}">
                                            {{ str($row->status)->headline() }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-sm text-[#64748b]">Belum ada transaksi untuk ditampilkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-5">
                <div class="rounded-[26px] bg-white px-5 py-5 shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Campaign Hampir Berakhir</p>
                    <div class="mt-4 space-y-3">
                        @forelse ($dashboard['ending_campaigns'] as $campaign)
                            <div class="rounded-2xl border border-[#e5edf6] bg-[#f8fafc] px-4 py-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-[#1e293b]">{{ $campaign['title'] }}</p>
                                        <p class="mt-1 text-xs text-pm-muted">{{ $campaign['days_left'] }}</p>
                                    </div>
                                    <span class="rounded-full bg-pm-blue-soft px-2 py-1 text-[10px] font-semibold text-pm-blue">{{ $campaign['progress'] }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-[#e2e8f0] px-4 py-5 text-sm text-[#64748b]">
                                Belum ada campaign dengan tanggal selesai yang tercatat.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-[24px] sm:rounded-[26px] bg-[linear-gradient(135deg,#0f172a,#065f46,#059669)] px-4 py-5 sm:px-5 sm:py-6 text-white shadow-[0_14px_28px_rgba(15,23,42,0.14)]">
                    <p class="text-[10px] uppercase tracking-[0.18em] text-emerald-200">AI Financial & Fundraising Analyst</p>
                    <h3 class="mt-2 font-display text-xl sm:text-2xl leading-tight">Analisis Laporan Keuangan dengan AI</h3>
                    <p class="mt-2 sm:mt-2.5 text-xs sm:text-sm leading-6 text-emerald-50/90">
                        Gunakan asisten AI berbasis FastAPI & Google Gemini untuk membedah tren arus kas, mendeteksi kampanye kritis, dan mengevaluasi retensi donatur secara instan.
                    </p>
                    <div class="mt-4">
                        <a
                            href="{{ route('admin.donations', ['open_ai' => 1]) }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-full bg-white px-4 py-2.5 text-xs font-bold text-[#065f46] shadow-sm transition hover:bg-emerald-50"
                        >
                            Buka AI Analis Keuangan
                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
