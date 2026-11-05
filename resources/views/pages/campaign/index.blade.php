@extends('layouts.public')

@section('content')
    <div class="mx-auto w-[min(1120px,calc(100%-1rem))] space-y-5">
        <section class="rounded-[24px] bg-[linear-gradient(135deg,#1e293b,#059669)] px-5 py-6 text-white shadow-[0_18px_40px_rgba(15,23,42,0.16)] sm:rounded-[30px] sm:px-8 sm:py-8">
            <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr] lg:items-end">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.28em] text-emerald-100">Campaign Directory</p>
                    <h1 class="mt-3 font-display text-[2.45rem] leading-none sm:text-[3.2rem]">Semua campaign dalam satu halaman yang mudah dipindai.</h1>
                    <p class="mt-4 max-w-2xl text-sm leading-7 text-emerald-50/90">
                        Halaman listing ini disiapkan sesuai brief: ada filter status dan kategori, grid campaign, serta panel donasi terbaru yang bisa membantu publik melihat aktivitas dukungan.
                    </p>
                </div>

                <div class="rounded-[24px] border border-white/12 bg-white/10 p-5 backdrop-blur-sm">
                    <p class="text-[10px] uppercase tracking-[0.18em] text-emerald-100">Ringkasan</p>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-2xl bg-white/10 px-4 py-3">
                            <p class="text-[10px] uppercase tracking-[0.14em] text-emerald-100">Total Campaign</p>
                            <p class="mt-2 font-display text-3xl leading-none">{{ $campaigns->count() }}</p>
                        </div>
                        <div class="rounded-2xl bg-white/10 px-4 py-3">
                            <p class="text-[10px] uppercase tracking-[0.14em] text-emerald-100">Campaign Aktif</p>
                            <p class="mt-2 font-display text-3xl leading-none">{{ $campaigns->where('status', 'active')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-5 lg:grid-cols-[1fr_0.34fr]">
            <div class="rounded-[28px] bg-white shadow-[0_16px_34px_rgba(15,23,42,0.08)]">
                <div class="border-b border-[#e7edf5] px-4 py-4 sm:px-6 sm:py-5">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Filter Campaign</p>
                            <h2 class="mt-1 font-display text-[2rem] leading-none text-[#1e293b] sm:text-3xl">Pilih berdasarkan status atau kategori.</h2>
                        </div>

                        <div class="flex flex-wrap gap-2" id="campaign-filters">
                            @foreach ($filters as $filter)
                                <button
                                    type="button"
                                    class="rounded-full border border-[#e2e8f0] bg-[#f8fafc] px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.08em] text-pm-blue transition duration-150 {{ $loop->first ? 'border-pm-blue bg-pm-blue text-white' : '' }}"
                                    data-filter="{{ $filter['value'] }}"
                                >
                                    {{ $filter['label'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="px-4 py-4 sm:px-6 sm:py-5">
                    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3" id="campaign-grid">
                        @foreach ($campaigns as $campaign)
                            @include('pages.partials.campaign-card', ['campaign' => $campaign])
                        @endforeach
                    </div>

                    <div class="mt-6 flex justify-center">
                        <button type="button" class="rounded-full bg-[#1e293b] px-5 py-2.5 text-[12px] font-medium text-white shadow-[0_10px_18px_rgba(15,23,42,0.16)]">
                            Load More Campaigns
                        </button>
                    </div>
                </div>
            </div>

            <aside class="space-y-5">
                <div class="rounded-[24px] bg-white px-4 py-5 shadow-[0_16px_34px_rgba(15,23,42,0.08)] sm:rounded-[28px] sm:px-5 sm:py-6">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Donasi Terbaru</p>
                    <div class="mt-4 space-y-3">
                        @foreach ($latestDonations as $donation)
                            <div class="rounded-2xl border border-[#e2e8f0] bg-[#f9fbff] px-4 py-3">
                                <p class="text-sm font-semibold text-[#15375f]">{{ $donation['name'] }}</p>
                                <p class="mt-1 text-xs text-pm-muted">{{ $donation['campaign'] }}</p>
                                <div class="mt-3 flex items-center justify-between text-xs text-pm-muted">
                                    <span>{{ $donation['date'] }}</span>
                                    <span class="font-semibold text-pm-blue">{{ $donation['amount'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-[24px] bg-[linear-gradient(135deg,#1e293b,#059669)] px-4 py-5 text-white shadow-[0_16px_34px_rgba(15,23,42,0.12)] sm:rounded-[28px] sm:px-5 sm:py-6">
                    <p class="text-[10px] uppercase tracking-[0.18em] text-emerald-100">Catatan Brief</p>
                    <p class="mt-3 text-sm leading-7 text-emerald-50/90">
                        Listing campaign memang sebaiknya berdiri sendiri, bukan sekadar section di home, agar nanti mudah dipasangkan pagination, SEO-friendly route, dan panel aktivitas terbaru.
                    </p>
                </div>
            </aside>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const filterButtons = Array.from(document.querySelectorAll('[data-filter]'));
            const campaignCards = Array.from(document.querySelectorAll('#campaign-grid [data-category]'));

            filterButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const selected = button.dataset.filter;

                    filterButtons.forEach((item) => {
                        item.classList.toggle('border-pm-blue', item === button);
                        item.classList.toggle('bg-pm-blue', item === button);
                        item.classList.toggle('text-white', item === button);
                        item.classList.toggle('text-pm-blue', item !== button);
                        item.classList.toggle('border-[#e2e8f0]', item !== button);
                        item.classList.toggle('bg-[#f8fafc]', item !== button);
                    });

                    campaignCards.forEach((card) => {
                        const byAll = selected === 'all';
                        const byStatus = card.dataset.status === selected;
                        const byCategory = card.dataset.category === selected;

                        card.hidden = !(byAll || byStatus || byCategory);
                    });
                });
            });
        })();
    </script>
@endpush
