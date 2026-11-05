@extends('layouts.public')

@section('content')
    <div class="mx-auto w-[min(1120px,calc(100%-1rem))] space-y-5">
        <section class="grid gap-5 lg:grid-cols-[0.95fr_1.05fr]">
            <div class="overflow-hidden rounded-[24px] bg-[linear-gradient(135deg,#1e293b,#059669)] shadow-[0_18px_40px_rgba(15,23,42,0.16)] sm:rounded-[30px]">
                <div class="relative min-h-[280px] p-5 text-white sm:min-h-[360px] sm:p-6" style="background: {{ $campaign['cover'] }};">
                    <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(4,19,37,0.10),rgba(4,19,37,0.36))]"></div>
                    <div class="relative flex h-full flex-col justify-between">
                        <div class="flex items-start justify-between gap-3">
                            <span class="rounded-full border border-white/20 bg-white/12 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.18em]">{{ $campaign['status_label'] }}</span>
                            <span class="rounded-full bg-black/20 px-3 py-1 text-[10px]">{{ $campaign['days_left'] }}</span>
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-emerald-100">{{ $campaign['organizer'] }}</p>
                            <h1 class="mt-3 max-w-lg font-display text-[2.35rem] leading-[0.92] tracking-tight sm:text-[3.2rem]">{{ $campaign['title'] }}</h1>
                            <p class="mt-4 max-w-xl text-sm leading-6 sm:leading-7 text-emerald-50/90">{{ $campaign['summary'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <div class="rounded-[24px] bg-white px-4 py-5 shadow-[0_16px_34px_rgba(15,23,42,0.08)] sm:rounded-[28px] sm:px-5 sm:py-6">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Progress Campaign</p>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-xs text-pm-muted">Terkumpul</p>
                            <p class="mt-2 font-display text-[2rem] leading-none text-[#1e293b] sm:text-4xl">{{ $campaign['collected'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-pm-muted">Target</p>
                            <p class="mt-2 font-display text-[2rem] leading-none text-[#1e293b] sm:text-4xl">{{ $campaign['target'] }}</p>
                        </div>
                    </div>

                    <div class="mt-4 h-2 rounded-full bg-[#e2e8f0]">
                        <div class="h-full rounded-full bg-pm-blue" style="width: {{ $campaign['progress'] }}%"></div>
                    </div>

                    <div class="mt-3 grid gap-3 sm:grid-cols-3">
                        <div class="rounded-2xl bg-[#f8fafc] px-4 py-3">
                            <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Progress</p>
                            <p class="mt-2 text-lg font-semibold text-[#1e293b]">{{ $campaign['progress'] }}%</p>
                        </div>
                        <div class="rounded-2xl bg-[#f8fafc] px-4 py-3">
                            <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Donatur</p>
                            <p class="mt-2 text-lg font-semibold text-[#1e293b]">{{ $campaign['donor_count'] }}</p>
                        </div>
                        <div class="rounded-2xl bg-[#f8fafc] px-4 py-3">
                            <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">Sisa Waktu</p>
                            <p class="mt-2 text-lg font-semibold text-[#1e293b]">{{ $campaign['days_left'] }}</p>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-3">
                        @if ($campaign['status'] === 'active')
                            <a href="{{ route('donation.create', ['slug' => $campaign['slug']]) }}" class="inline-flex rounded-full bg-pm-blue px-5 py-3 text-sm font-semibold text-white">Donasi Sekarang</a>
                        @endif
                        <a href="{{ route('donation.lookup') }}" class="inline-flex rounded-full border border-[#d9e2ef] bg-[#f8fafc] px-3.5 py-2.5 text-sm font-medium text-[#334155] sm:px-4 sm:py-3">
                            Cek Status Donasi
                        </a>
                        @foreach ($campaign['share_links'] as $link)
                            <a href="{{ $link['url'] }}" class="inline-flex rounded-full border border-[#d9e2ef] bg-[#f8fafc] px-3.5 py-2.5 text-sm font-medium text-[#334155] sm:px-4 sm:py-3">
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-[24px] bg-white px-4 py-5 shadow-[0_16px_34px_rgba(15,23,42,0.08)] sm:rounded-[28px] sm:px-5 sm:py-6">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Informasi Khusus</p>
                    <div class="mt-4 grid gap-3">
                        @foreach ($campaign['meta'] as $label => $value)
                            <div class="flex flex-col gap-1 rounded-2xl border border-[#e2e8f0] px-4 py-3 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                                <span class="text-sm text-pm-muted">{{ $label }}</span>
                                <span class="text-sm font-semibold text-[#1e293b]">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-5 lg:grid-cols-[1fr_0.34fr]">
            <div class="rounded-[24px] bg-white shadow-[0_16px_34px_rgba(15,23,42,0.08)] sm:rounded-[28px]">
                <div class="border-b border-[#e7edf5] px-4 py-4 sm:px-6 sm:py-5">
                    <div class="flex flex-wrap gap-2" id="detail-tabs">
                        <button type="button" class="rounded-full border border-pm-blue bg-pm-blue px-4 py-2 text-sm font-semibold text-white" data-tab-target="summary">Rangkuman</button>
                        <button type="button" class="rounded-full border border-[#e2e8f0] bg-[#f8fafc] px-4 py-2 text-sm font-semibold text-pm-blue" data-tab-target="updates">Kabar Terbaru</button>
                        <button type="button" class="rounded-full border border-[#e2e8f0] bg-[#f8fafc] px-4 py-2 text-sm font-semibold text-pm-blue" data-tab-target="donors">Donatur</button>
                        <button type="button" class="rounded-full border border-[#e2e8f0] bg-[#f8fafc] px-4 py-2 text-sm font-semibold text-pm-blue" data-tab-target="prayers">Doa & Pesan</button>
                    </div>
                </div>

                <div class="px-4 py-5 sm:px-6 sm:py-6">
                    <div data-tab-panel="summary">
                        <h2 class="font-display text-[2rem] leading-none text-[#1e293b] sm:text-3xl">Rangkuman Campaign</h2>
                        <div class="mt-4 space-y-4 text-sm leading-7 text-[#334155]">
                            @forelse ($campaign['story'] as $paragraph)
                                <p>{{ $paragraph }}</p>
                            @empty
                                <p>Konten campaign belum diisi dari CMS.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="hidden space-y-4" data-tab-panel="updates">
                        @forelse ($campaign['updates'] as $update)
                            <article class="rounded-[24px] border border-[#e2e8f0] bg-[#ffffff] px-4 py-4 sm:px-5 sm:py-5">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <h3 class="font-display text-[1.7rem] leading-none text-[#1e293b] sm:text-2xl">{{ $update['title'] }}</h3>
                                    <span class="text-xs font-semibold uppercase tracking-[0.12em] text-pm-muted">{{ $update['date'] }}</span>
                                </div>
                                <p class="mt-3 text-sm leading-7 text-[#334155]">{{ $update['body'] }}</p>
                            </article>
                        @empty
                            <div class="rounded-[24px] border border-dashed border-[#e2e8f0] px-5 py-8 text-sm text-[#64748b]">
                                Belum ada kabar terbaru untuk campaign ini.
                            </div>
                        @endforelse
                    </div>

                    <div class="hidden space-y-4" data-tab-panel="donors">
                        @forelse ($campaign['donors'] as $donor)
                            <div class="rounded-[22px] border border-[#e2e8f0] bg-[#ffffff] px-4 py-4 sm:px-5">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between sm:gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-[#1e293b]">{{ $donor['name'] }}</p>
                                        <p class="mt-1 text-xs text-pm-muted">{{ $donor['date'] }}</p>
                                    </div>
                                    <p class="text-sm font-semibold text-pm-blue">{{ $donor['amount'] }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-[24px] border border-dashed border-[#e2e8f0] px-5 py-8 text-sm text-[#64748b]">
                                Belum ada donasi publik yang bisa ditampilkan.
                            </div>
                        @endforelse
                    </div>

                    <div class="hidden space-y-4" data-tab-panel="prayers">
                        @forelse ($prayerMessages as $prayer)
                            <div class="rounded-[22px] border border-[#e2e8f0] bg-[#ffffff] px-4 py-4 sm:px-5">
                                <div class="flex flex-col gap-3">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-semibold text-[#1e293b]">{{ $prayer['name'] }}</p>
                                        <p class="text-xs text-pm-muted">{{ $prayer['date'] }}</p>
                                    </div>
                                    <p class="text-sm leading-6 text-[#334155] italic">"{{ $prayer['message'] }}"</p>
                                    <p class="text-xs font-semibold text-pm-blue">{{ $prayer['amount'] }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-[24px] border border-dashed border-[#e2e8f0] px-5 py-8 text-sm text-[#64748b]">
                                Belum ada pesan atau doa dari donatur.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <aside class="space-y-5">
                <div class="rounded-[24px] bg-white px-4 py-5 shadow-[0_16px_34px_rgba(15,23,42,0.08)] sm:rounded-[28px] sm:px-5 sm:py-6">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Donasi Terbaru</p>
                    <div class="mt-4 space-y-3">
                        @forelse ($latestDonations as $donation)
                            <div class="rounded-2xl border border-[#e2e8f0] bg-[#f9fbff] px-4 py-3">
                                <p class="text-sm font-semibold text-[#15375f]">{{ $donation['name'] }}</p>
                                <div class="mt-3 flex items-center justify-between text-xs text-pm-muted">
                                    <span>{{ $donation['date'] }}</span>
                                    <span class="font-semibold text-pm-blue">{{ $donation['amount'] }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-[24px] border border-dashed border-[#e2e8f0] px-4 py-6 text-sm text-[#64748b]">
                                Belum ada donasi terbaru.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-[24px] bg-[linear-gradient(135deg,#1e293b,#059669)] px-4 py-5 text-white shadow-[0_16px_34px_rgba(15,23,42,0.12)] sm:rounded-[28px] sm:px-5 sm:py-6">
                    <p class="text-[10px] uppercase tracking-[0.18em] text-emerald-100">Campaign Terkait</p>
                    <div class="mt-4 space-y-3">
                        @forelse ($relatedCampaigns as $related)
                            <a href="{{ route('campaign.show', ['slug' => $related['slug']]) }}" class="block rounded-2xl border border-white/12 bg-white/10 px-4 py-4">
                                <p class="text-sm font-semibold text-white">{{ $related['title'] }}</p>
                                <p class="mt-1 text-xs text-emerald-100">{{ $related['days_left'] }}</p>
                            </a>
                        @empty
                            <div class="rounded-2xl border border-white/12 bg-white/10 px-4 py-4 text-sm text-emerald-50/90">
                                Belum ada campaign terkait dalam kategori yang sama.
                            </div>
                        @endforelse
                    </div>
                </div>
            </aside>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const buttons = Array.from(document.querySelectorAll('[data-tab-target]'));
            const panels = Array.from(document.querySelectorAll('[data-tab-panel]'));

            buttons.forEach((button) => {
                button.addEventListener('click', () => {
                    const target = button.dataset.tabTarget;

                    buttons.forEach((item) => {
                        item.classList.toggle('border-pm-blue', item === button);
                        item.classList.toggle('bg-pm-blue', item === button);
                        item.classList.toggle('text-white', item === button);
                        item.classList.toggle('text-pm-blue', item !== button);
                        item.classList.toggle('border-[#e2e8f0]', item !== button);
                        item.classList.toggle('bg-[#f8fafc]', item !== button);
                    });

                    panels.forEach((panel) => {
                        panel.classList.toggle('hidden', panel.dataset.tabPanel !== target);
                    });
                });
            });
        })();
    </script>
@endpush
