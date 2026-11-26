@extends('layouts.public')

@section('content')
    @php
        $progressVal = (int) ($campaign['progress'] ?? 0);
        $isUrgent = $progressVal < 35 && ($campaign['status'] ?? '') === 'active';
    @endphp

    {{-- Top Editorial Overview --}}
    <section class="border-b border-line bg-canvas py-8 sm:py-12">
        <div class="mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="mb-5 flex items-center gap-2 text-xs text-muted">
                <a href="{{ route('home') }}" class="hover:text-ink">Beranda</a>
                <span>/</span>
                <a href="{{ route('campaign.index') }}" class="hover:text-ink">Program</a>
                <span>/</span>
                <span class="truncate text-ink">{{ $campaign['title'] }}</span>
            </div>

            <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-start">
                {{-- Cover Story Visual --}}
                <div class="overflow-hidden rounded-xl border border-line bg-brand-deep text-white">
                    <div class="relative flex min-h-[320px] flex-col justify-between p-6 sm:min-h-[400px] sm:p-8" style="background: {{ $campaign['cover'] }};">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0B1418]/85 via-[#0B1418]/30 to-[#0B1418]/15"></div>
                        <div class="relative flex items-center justify-between gap-3 text-xs">
                            <span class="rounded-md bg-black/35 px-2.5 py-1 font-medium backdrop-blur-[2px]">{{ $campaign['status_label'] }}</span>
                            <span class="rounded-md bg-black/35 px-2.5 py-1 font-medium tabular-nums backdrop-blur-[2px]">{{ $campaign['days_left'] }}</span>
                        </div>

                        <div class="relative space-y-3">
                            <p class="text-xs font-medium text-[#E8C89B]">{{ $campaign['organizer'] }}</p>
                            <h1 class="font-display text-3xl font-semibold leading-[1.1] text-white sm:text-4xl">
                                {{ $campaign['title'] }}
                            </h1>
                            <p class="max-w-xl text-sm leading-relaxed text-white/85">
                                {{ $campaign['summary'] }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Donation Progress & Action Panel --}}
                <div class="space-y-5">
                    <div class="rounded-xl border border-line bg-surface p-6 tabular-nums">
                        <div class="flex items-baseline justify-between gap-4">
                            <div>
                                <p class="text-xs text-muted">Dana terkumpul</p>
                                <p class="mt-1 font-display text-3xl font-semibold text-brand">{{ $campaign['collected'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-muted">Target</p>
                                <p class="mt-1 text-sm font-semibold text-ink">{{ $campaign['target'] }}</p>
                            </div>
                        </div>

                        <div class="mt-4 h-2 overflow-hidden rounded-full bg-surface-subtle">
                            <div
                                class="h-full rounded-full {{ $isUrgent ? 'bg-ochre' : 'bg-brand' }}"
                                style="width: {{ min(100, max(0, $progressVal)) }}%"
                            ></div>
                        </div>

                        <div class="mt-5 grid grid-cols-3 divide-x divide-line border-y border-line py-3.5 text-center">
                            <div>
                                <p class="text-[11px] text-muted">Capaian</p>
                                <p class="mt-1 text-sm font-bold text-ink">{{ $progressVal }}%</p>
                            </div>
                            <div>
                                <p class="text-[11px] text-muted">Donatur</p>
                                <p class="mt-1 text-sm font-bold text-ink">{{ $campaign['donor_count'] }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] text-muted">Sisa waktu</p>
                                <p class="mt-1 text-sm font-bold text-ink">{{ $campaign['days_left'] }}</p>
                            </div>
                        </div>

                        <div class="mt-5 flex flex-col gap-2.5 sm:flex-row">
                            @if ($campaign['status'] === 'active')
                                <a
                                    href="{{ route('donation.create', ['slug' => $campaign['slug']]) }}"
                                    class="inline-flex flex-1 items-center justify-center rounded-lg bg-brand px-5 py-3 text-sm font-semibold text-white transition-colors hover:bg-brand-deep"
                                >
                                    Donasi sekarang
                                </a>
                            @endif
                            <a
                                href="{{ route('donation.lookup') }}"
                                class="inline-flex items-center justify-center rounded-lg border border-line-strong bg-surface px-4 py-3 text-sm font-medium text-ink transition-colors hover:bg-surface-subtle"
                            >
                                Cek status
                            </a>
                        </div>

                        @if (! empty($campaign['share_links']))
                            <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-line pt-4">
                                <span class="text-xs text-muted">Bagikan:</span>
                                @foreach ($campaign['share_links'] as $link)
                                    <a
                                        href="{{ $link['url'] }}"
                                        class="rounded-md border border-line bg-canvas px-3 py-1.5 text-xs font-medium text-ink-soft transition-colors hover:border-line-strong hover:text-ink"
                                    >
                                        {{ $link['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if (! empty($campaign['meta']))
                        <div class="rounded-xl border border-line bg-surface p-5">
                            <h2 class="text-xs font-semibold text-ink">Informasi program</h2>
                            <dl class="mt-3 divide-y divide-line text-xs">
                                @foreach ($campaign['meta'] as $label => $value)
                                    <div class="flex flex-col gap-1 py-2.5 sm:flex-row sm:items-center sm:justify-between">
                                        <dt class="text-muted">{{ $label }}</dt>
                                        <dd class="font-semibold text-ink">{{ $value }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Detail Tabs & Sidebar --}}
    <section class="bg-surface py-10 sm:py-12">
        <div class="mx-auto grid w-full max-w-6xl gap-8 px-4 sm:px-6 lg:grid-cols-[1fr_320px] lg:px-8">
            <div class="min-w-0">
                <div class="flex flex-wrap gap-1.5 border-b border-line pb-4" id="detail-tabs">
                    <button type="button" class="rounded-md bg-ink px-3.5 py-2 text-xs font-semibold text-white" data-tab-target="summary">Rangkuman</button>
                    <button type="button" class="rounded-md border border-line bg-canvas px-3.5 py-2 text-xs font-medium text-muted hover:text-ink" data-tab-target="updates">Kabar terbaru</button>
                    <button type="button" class="rounded-md border border-line bg-canvas px-3.5 py-2 text-xs font-medium text-muted hover:text-ink" data-tab-target="donors">Donatur</button>
                    <button type="button" class="rounded-md border border-line bg-canvas px-3.5 py-2 text-xs font-medium text-muted hover:text-ink" data-tab-target="prayers">Doa &amp; pesan</button>
                </div>

                <div class="pt-6">
                    <div data-tab-panel="summary" class="max-w-3xl">
                        <h2 class="font-display text-2xl font-semibold text-ink">Rangkuman program</h2>
                        <div class="mt-4 space-y-4 text-sm leading-7 text-ink-soft">
                            @forelse ($campaign['story'] as $paragraph)
                                <p>{{ $paragraph }}</p>
                            @empty
                                <p class="text-muted">Konten campaign belum diisi.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="hidden space-y-4" data-tab-panel="updates">
                        @forelse ($campaign['updates'] as $update)
                            <article class="rounded-xl border border-line bg-canvas p-5">
                                <div class="flex flex-wrap items-baseline justify-between gap-2">
                                    <h3 class="font-display text-xl font-semibold text-ink">{{ $update['title'] }}</h3>
                                    <span class="text-xs text-muted">{{ $update['date'] }}</span>
                                </div>
                                <p class="mt-3 text-sm leading-7 text-ink-soft">{{ $update['body'] }}</p>
                            </article>
                        @empty
                            <div class="rounded-xl border border-dashed border-line-strong bg-canvas p-6 text-sm text-muted">
                                Belum ada kabar terbaru untuk program ini.
                            </div>
                        @endforelse
                    </div>

                    <div class="hidden divide-y divide-line rounded-xl border border-line bg-canvas" data-tab-panel="donors">
                        @forelse ($campaign['donors'] as $donor)
                            <div class="flex flex-col gap-1 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-ink">{{ $donor['name'] }}</p>
                                    <p class="text-xs text-muted">{{ $donor['date'] }}</p>
                                </div>
                                <p class="text-sm font-semibold text-brand tabular-nums">{{ $donor['amount'] }}</p>
                            </div>
                        @empty
                            <div class="p-6 text-sm text-muted">
                                Belum ada donasi publik yang dapat ditampilkan.
                            </div>
                        @endforelse
                    </div>

                    <div class="hidden space-y-4" data-tab-panel="prayers">
                        @forelse ($prayerMessages as $prayer)
                            <article class="rounded-xl border border-line bg-canvas p-5">
                                <div class="flex items-center justify-between gap-2 text-xs">
                                    <span class="font-semibold text-ink">{{ $prayer['name'] }}</span>
                                    <span class="text-muted">{{ $prayer['date'] }}</span>
                                </div>
                                <p class="mt-2.5 text-sm italic leading-relaxed text-ink-soft">"{{ $prayer['message'] }}"</p>
                                <p class="mt-3 text-xs font-semibold text-brand tabular-nums">{{ $prayer['amount'] }}</p>
                            </article>
                        @empty
                            <div class="rounded-xl border border-dashed border-line-strong bg-canvas p-6 text-sm text-muted">
                                Belum ada pesan atau doa dari donatur.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="rounded-xl border border-line bg-canvas p-5">
                    <h3 class="text-sm font-semibold text-ink">Donasi terbaru</h3>
                    <div class="mt-4 divide-y divide-line">
                        @forelse ($latestDonations as $donation)
                            <div class="py-3 first:pt-0 last:pb-0">
                                <div class="flex items-baseline justify-between gap-2">
                                    <p class="text-xs font-semibold text-ink">{{ $donation['name'] }}</p>
                                    <span class="text-xs font-semibold text-brand tabular-nums">{{ $donation['amount'] }}</span>
                                </div>
                                <p class="mt-1 text-[11px] text-muted">{{ $donation['date'] }}</p>
                            </div>
                        @empty
                            <p class="py-3 text-xs text-muted">Belum ada donasi terbaru.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-xl border border-line bg-brand-deep p-5 text-white">
                    <h3 class="text-sm font-semibold">Program terkait</h3>
                    <div class="mt-3.5 space-y-2.5">
                        @forelse ($relatedCampaigns as $related)
                            <a
                                href="{{ route('campaign.show', ['slug' => $related['slug']]) }}"
                                class="block rounded-lg border border-white/12 bg-white/5 p-3.5 transition-colors hover:bg-white/10"
                            >
                                <p class="text-xs font-semibold text-white">{{ $related['title'] }}</p>
                                <p class="mt-1 text-[11px] text-white/70">{{ $related['days_left'] }}</p>
                            </a>
                        @empty
                            <p class="text-xs text-white/75">Belum ada program terkait dalam kategori yang sama.</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </section>
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
                        const isActive = item === button;
                        item.classList.toggle('bg-ink', isActive);
                        item.classList.toggle('text-white', isActive);
                        item.classList.toggle('font-semibold', isActive);
                        item.classList.toggle('border', !isActive);
                        item.classList.toggle('border-line', !isActive);
                        item.classList.toggle('bg-canvas', !isActive);
                        item.classList.toggle('text-muted', !isActive);
                    });

                    panels.forEach((panel) => {
                        panel.classList.toggle('hidden', panel.dataset.tabPanel !== target);
                    });
                });
            });
        })();
    </script>
@endpush
