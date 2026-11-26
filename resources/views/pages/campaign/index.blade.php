@extends('layouts.public')

@section('content')
    {{-- Page Header --}}
    <section class="border-b border-line bg-canvas py-10 sm:py-12">
        <div class="mx-auto flex w-full max-w-6xl flex-col gap-6 px-4 sm:px-6 lg:flex-row lg:items-end lg:justify-between lg:px-8">
            <div class="max-w-2xl">
                <p class="text-xs font-medium text-brand">Direktori program</p>
                <h1 class="mt-2 font-display text-3xl font-semibold leading-tight text-ink sm:text-5xl">
                    Semua program kebaikan dalam satu halaman.
                </h1>
                <p class="mt-3 text-sm leading-7 text-muted">
                    Pilih berdasarkan kategori atau status, lalu pantau perkembangan dana serta donasi terbaru secara transparan.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4 rounded-xl border border-line bg-surface px-5 py-4 tabular-nums">
                <div>
                    <p class="text-xs text-muted">Total program</p>
                    <p class="mt-1 font-display text-2xl font-semibold text-ink">{{ $campaigns->count() }}</p>
                </div>
                <div class="border-l border-line pl-4">
                    <p class="text-xs text-muted">Sedang aktif</p>
                    <p class="mt-1 font-display text-2xl font-semibold text-brand">{{ $campaigns->where('status', 'active')->count() }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Directory Grid & Latest Donations Sidebar --}}
    <section class="bg-surface py-10 sm:py-12">
        <div class="mx-auto grid w-full max-w-6xl gap-8 px-4 sm:px-6 lg:grid-cols-[1fr_300px] lg:px-8">
            <div>
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-line pb-5">
                    <h2 class="font-display text-2xl font-semibold text-ink">Daftar campaign</h2>
                    <div class="flex flex-wrap gap-1.5" id="campaign-filters">
                        @foreach ($filters as $filter)
                            <button
                                type="button"
                                class="rounded-md px-3 py-1.5 text-xs font-medium transition-colors {{ $loop->first ? 'bg-ink text-white' : 'border border-line bg-canvas text-muted hover:border-line-strong hover:text-ink' }}"
                                data-filter="{{ $filter['value'] }}"
                            >
                                {{ $filter['label'] }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6 grid gap-5 sm:grid-cols-2" id="campaign-grid">
                    @foreach ($campaigns as $campaign)
                        @include('pages.partials.campaign-card', ['campaign' => $campaign])
                    @endforeach
                </div>
            </div>

            <aside class="space-y-6">
                <div class="rounded-xl border border-line bg-canvas p-5">
                    <h3 class="text-sm font-semibold text-ink">Donasi terbaru</h3>
                    <p class="mt-1 text-xs text-muted">Aktivitas dukungan yang baru terverifikasi.</p>

                    <div class="mt-4 divide-y divide-line">
                        @forelse ($latestDonations as $donation)
                            <div class="py-3 first:pt-0 last:pb-0">
                                <div class="flex items-baseline justify-between gap-2">
                                    <p class="text-xs font-semibold text-ink">{{ $donation['name'] }}</p>
                                    <span class="text-xs font-semibold text-brand tabular-nums">{{ $donation['amount'] }}</span>
                                </div>
                                <p class="mt-1 text-xs text-muted line-clamp-1">{{ $donation['campaign'] }}</p>
                                <p class="mt-1 text-[11px] text-muted">{{ $donation['date'] }}</p>
                            </div>
                        @empty
                            <p class="py-4 text-xs text-muted">Belum ada donasi terbaru.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-xl border border-line bg-brand-deep p-5 text-white">
                    <h3 class="font-display text-lg font-semibold">Sudah pernah berdonasi?</h3>
                    <p class="mt-2 text-xs leading-relaxed text-white/80">
                        Lacak status verifikasi dan unduh invoice PDF kapan saja menggunakan nomor referensi donasi Anda.
                    </p>
                    <a
                        href="{{ route('donation.lookup') }}"
                        class="mt-4 inline-flex items-center rounded-lg bg-surface px-4 py-2 text-xs font-semibold text-ink transition-colors hover:bg-brand-soft hover:text-brand"
                    >
                        Cek status donasi
                    </a>
                </div>
            </aside>
        </div>
    </section>
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
                        const isActive = item === button;
                        item.classList.toggle('bg-ink', isActive);
                        item.classList.toggle('text-white', isActive);
                        item.classList.toggle('border', !isActive);
                        item.classList.toggle('border-line', !isActive);
                        item.classList.toggle('bg-canvas', !isActive);
                        item.classList.toggle('text-muted', !isActive);
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
