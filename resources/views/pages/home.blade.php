@extends('layouts.public')

@section('content')
    <div class="mx-auto w-[min(1120px,calc(100%-1rem))] space-y-4">
        <section class="overflow-hidden rounded-[14px] bg-white shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
            <div class="flex flex-col gap-3 px-4 py-4 sm:px-6">
                <div class="rounded-md bg-pm-blue px-4 py-3 text-white shadow-[inset_0_0_0_1px_rgba(255,255,255,0.15)]">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-display text-[1.7rem] leading-none tracking-tight sm:text-[2.4rem]">{{ $site['name'] }}</p>
                            <p class="mt-1 text-[11px] uppercase tracking-[0.3em] text-emerald-100">{{ $site['tagline'] }}</p>
                        </div>

                        <div class="flex flex-wrap gap-2 text-[10px]">
                            <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-emerald-50">Program terverifikasi</span>
                            <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-emerald-50">Laporan progres berkala</span>
                        </div>
                    </div>

                    <div class="mt-3 flex flex-wrap gap-3 border-t border-white/10 pt-3">
                        <a href="{{ route('donation.lookup') }}" class="inline-flex items-center rounded-full bg-white px-4 py-2 text-xs font-semibold text-pm-blue transition hover:bg-emerald-50">
                            Cek Status Donasi
                        </a>
                        <p class="self-center text-[11px] text-emerald-100/90">Sudah berdonasi? Gunakan nomor referensi untuk melihat status tanpa login.</p>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-[16px] bg-pm-blue">
                    <div id="hero-slides">
                        @foreach ($heroSlides as $index => $slide)
                            <article
                                class="{{ $index === 0 ? 'block' : 'hidden' }} bg-cover bg-center"
                                data-hero-slide
                                @if (! empty($slide['image_url']))
                                    style="background-image: linear-gradient(135deg, rgba(10,40,77,0.35), rgba(10,40,77,0.58)), url('{{ $slide['image_url'] }}');"
                                @endif
                            >
                                <div class="grid min-h-[250px] gap-5 bg-[linear-gradient(135deg,rgba(10,40,77,0.10),rgba(10,40,77,0.34))] px-4 py-5 text-white sm:min-h-[340px] sm:px-7 sm:py-6 lg:grid-cols-[0.95fr_1.05fr] lg:items-end">
                                    <div class="flex items-start sm:items-end">
                                        <div class="max-w-xs rounded-[18px] border border-white/15 bg-black/15 p-4 backdrop-blur-sm">
                                            <p class="text-[10px] uppercase tracking-[0.22em] text-emerald-100">{{ $slide['eyebrow'] }}</p>
                                            <p class="mt-2 text-sm leading-6 text-emerald-50/90">{{ $slide['description'] }}</p>
                                            <a class="mt-4 inline-flex rounded-full bg-white px-3 py-2 text-xs font-semibold text-pm-blue" href="{{ $slide['url'] ?? route('campaign.index') }}">
                                                {{ $slide['cta'] }}
                                            </a>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-start lg:justify-end">
                                        <div class="max-w-[420px] text-left lg:text-right">
                                            <p class="text-xs uppercase tracking-[0.22em] text-emerald-100">Koleksi Cerita Kebaikan</p>
                                            <p class="mt-3 font-display text-[2.55rem] leading-[0.9] tracking-tight text-white sm:text-[4.4rem]">
                                                {{ $slide['headline'] }}
                                            </p>
                                            <p class="mt-4 max-w-sm text-sm leading-6 text-emerald-50/85 lg:ml-auto">{{ $slide['description'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="absolute inset-x-0 bottom-3 flex items-center justify-between px-4 sm:px-6">
                        <button type="button" class="flex h-8 w-8 items-center justify-center rounded-full bg-black/20 text-lg text-white" data-hero-prev>&lsaquo;</button>

                        <div class="flex items-center gap-2">
                            @foreach ($heroSlides as $index => $slide)
                                <button
                                    type="button"
                                    class="h-2 w-2 rounded-full bg-white/45 transition duration-150 {{ $index === 0 ? 'scale-110 bg-white' : '' }}"
                                    data-hero-dot
                                    data-slide-index="{{ $index }}"
                                    aria-label="Slide {{ $index + 1 }}"
                                ></button>
                            @endforeach
                        </div>

                        <button type="button" class="flex h-8 w-8 items-center justify-center rounded-full bg-black/20 text-lg text-white" data-hero-next>&rsaquo;</button>
                    </div>
                </div>
            </div>
        </section>

        <main class="space-y-4">
            <section class="rounded-[14px] bg-white shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
                <div class="border-b border-[#e7edf5] px-4 py-4 sm:px-6">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Pilihan Program</p>
                            <h2 class="mt-1 font-display text-[2.15rem] leading-none text-[#1e293b] sm:text-4xl">Temukan campaign yang paling relevan untuk didukung.</h2>
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

                <div class="px-4 py-4 sm:px-6">
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3" id="campaign-grid">
                        @forelse ($campaigns as $campaign)
                            @include('pages.partials.campaign-card', ['campaign' => $campaign])
                        @empty
                            <div class="col-span-full rounded-[22px] border border-dashed border-[#e2e8f0] bg-[#f8fafc] px-6 py-10 text-center text-sm text-[#64748b]">
                                Belum ada campaign yang dipublikasikan dari CMS.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6 flex justify-center">
                        <a href="{{ route('campaign.index') }}" class="rounded-full bg-[#1e293b] px-4 py-2 text-[12px] font-medium text-white">
                            Load campaigns
                        </a>
                    </div>
                </div>
            </section>

            <section class="grid gap-4 lg:grid-cols-[1.2fr_0.55fr]">
                <div class="overflow-hidden rounded-[18px] bg-pm-blue p-4 shadow-[0_14px_28px_rgba(15,23,42,0.12)]">
                    <div class="overflow-hidden rounded-[14px] bg-[linear-gradient(135deg,rgba(255,164,82,0.42),rgba(10,66,122,0.18))] px-5 py-8 text-center text-white sm:px-8">
                        <p class="font-display text-[1.65rem] leading-none">{{ $aboutPage['title'] ?? 'Tentang DonasiFlow Nusantara' }}</p>
                        <p class="mx-auto mt-3 max-w-2xl text-sm leading-6 text-emerald-50">{{ $aboutPage['intro'] ?? 'Halaman tentang kini sepenuhnya bisa dikelola dari CMS dan ditampilkan langsung di area publik.' }}</p>
                        <a href="{{ route('pages.show', ['slug' => 'about']) }}" class="mt-4 inline-flex rounded-full bg-pm-yellow px-4 py-2 text-xs font-semibold text-[#0f2647]">
                            Lihat Tentang
                        </a>
                    </div>
                </div>

                <div class="flex min-h-[180px] items-center justify-center rounded-[18px] bg-[linear-gradient(180deg,#f8fafc,#f8fafc)] px-4 text-center text-[#1e293b] shadow-[0_14px_28px_rgba(15,23,42,0.08)] sm:min-h-[220px]">
                    <div>
                        <p class="font-display text-5xl leading-none sm:text-6xl">{{ $impact['value'] }}</p>
                        <p class="mt-2 text-sm text-pm-muted">{{ $impact['label'] }}</p>
                    </div>
                </div>
            </section>

            <section class="grid gap-4 {{ count($testimonials) > 0 ? 'lg:grid-cols-[1.1fr_0.9fr]' : 'lg:grid-cols-1 mx-auto w-full max-w-3xl' }}">
                @if (count($testimonials) > 0)
                <div class="rounded-[18px] bg-white px-4 py-5 shadow-[0_14px_28px_rgba(15,23,42,0.08)] sm:px-6">
                    <div class="flex items-end justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Cerita Kepercayaan</p>
                            <h2 class="mt-1 font-display text-[2rem] leading-none text-[#1e293b] sm:text-[2.6rem]">Testimoni yang kini bisa dikelola dari CMS.</h2>
                        </div>
                        <a href="{{ route('pages.show', ['slug' => 'contact']) }}" class="hidden rounded-full border border-[#e2e8f0] bg-[#f8fafc] px-4 py-2 text-xs font-semibold text-pm-blue sm:inline-flex">Hubungi Tim</a>
                    </div>

                    <div class="mt-5 grid gap-3">
                        @forelse ($testimonials as $testimonial)
                            <article class="rounded-[22px] border border-[#e2e8f0] bg-[linear-gradient(180deg,#ffffff,#ffffff)] px-4 py-4">
                                <div class="flex items-center gap-1 text-[#f4a340]">
                                    @for ($i = 0; $i < $testimonial['rating']; $i++)
                                        <span>&#9733;</span>
                                    @endfor
                                </div>
                                <p class="mt-3 text-sm leading-7 text-[#334155]">"{{ $testimonial['quote'] }}"</p>
                                <div class="mt-4 border-t border-[#edf2f7] pt-3">
                                    <p class="text-sm font-semibold text-[#1e293b]">{{ $testimonial['name'] }}</p>
                                    <p class="mt-1 text-xs text-pm-muted">{{ collect([$testimonial['role'], $testimonial['location']])->filter()->implode(' - ') }}</p>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-[22px] border border-dashed border-[#e2e8f0] bg-[#f8fafc] px-6 py-10 text-center text-sm text-[#64748b]">
                                Belum ada testimoni aktif dari CMS.
                            </div>
                        @endforelse
                    </div>
                </div>
                @endif

                <div class="rounded-[18px] bg-[linear-gradient(180deg,#1e293b,#059669)] px-4 py-5 text-white shadow-[0_14px_28px_rgba(15,23,42,0.12)] sm:px-6">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-emerald-100">FAQ Singkat</p>
                    <h2 class="mt-1 font-display text-[2rem] leading-none sm:text-[2.5rem]">Pertanyaan yang paling sering ditanyakan.</h2>

                    <div class="mt-5 space-y-3">
                        @forelse (collect($faqs)->take(3) as $faq)
                            <article class="rounded-[20px] border border-white/12 bg-white/10 px-4 py-4">
                                <h3 class="text-sm font-semibold text-white">{{ $faq['question'] }}</h3>
                                <p class="mt-2 text-sm leading-6 text-emerald-50/85">{{ $faq['answer'] }}</p>
                            </article>
                        @empty
                            <div class="rounded-[20px] border border-white/12 bg-white/10 px-4 py-5 text-sm text-emerald-50/85">
                                Belum ada FAQ aktif dari CMS.
                            </div>
                        @endforelse
                    </div>

                    <a href="{{ route('pages.show', ['slug' => 'faq']) }}" class="mt-5 inline-flex rounded-full bg-white px-4 py-2 text-xs font-semibold text-pm-blue">
                        Lihat Semua FAQ
                    </a>
                </div>
            </section>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const slides = Array.from(document.querySelectorAll('[data-hero-slide]'));
            const dots = Array.from(document.querySelectorAll('[data-hero-dot]'));
            const prevButton = document.querySelector('[data-hero-prev]');
            const nextButton = document.querySelector('[data-hero-next]');
            const filterButtons = Array.from(document.querySelectorAll('[data-filter]'));
            const campaignCards = Array.from(document.querySelectorAll('#campaign-grid [data-category]'));

            let activeSlide = 0;
            let autoplay;

            const setSlide = (index) => {
                activeSlide = (index + slides.length) % slides.length;

                slides.forEach((slide, slideIndex) => {
                    slide.classList.toggle('hidden', slideIndex !== activeSlide);
                    slide.classList.toggle('block', slideIndex === activeSlide);
                });

                dots.forEach((dot, dotIndex) => {
                    dot.classList.toggle('bg-white', dotIndex === activeSlide);
                    dot.classList.toggle('scale-110', dotIndex === activeSlide);
                    dot.classList.toggle('bg-white/45', dotIndex !== activeSlide);
                });
            };

            const resetAutoplay = () => {
                window.clearInterval(autoplay);
                autoplay = window.setInterval(() => setSlide(activeSlide + 1), 6000);
            };

            dots.forEach((dot) => {
                dot.addEventListener('click', () => {
                    setSlide(Number(dot.dataset.slideIndex));
                    resetAutoplay();
                });
            });

            prevButton?.addEventListener('click', () => {
                setSlide(activeSlide - 1);
                resetAutoplay();
            });

            nextButton?.addEventListener('click', () => {
                setSlide(activeSlide + 1);
                resetAutoplay();
            });

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

            setSlide(0);
            resetAutoplay();
        })();
    </script>
@endpush
