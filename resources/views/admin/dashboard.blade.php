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

                <div class="rounded-[26px] bg-[linear-gradient(135deg,#1e293b,#059669)] px-5 py-6 text-white shadow-[0_14px_28px_rgba(15,23,42,0.14)]">
                    <p class="text-[10px] uppercase tracking-[0.18em] text-emerald-100">Arah Implementasi</p>
                    <p class="mt-3 text-sm leading-7 text-emerald-50/90">
                        Dashboard ini sudah disiapkan sebagai shell administrasi. Setelah migration dan model dibuat, tiap widget bisa langsung disambungkan ke data nyata tanpa perlu mengubah layout dasarnya.
                    </p>
                </div>
            </div>
        </section>
    </div>
@endsection
