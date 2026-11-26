@extends('layouts.public')

@section('content')
    {{-- Asymmetric Split Editorial Hero --}}
    <section class="border-b border-line bg-canvas">
        <div class="mx-auto grid w-full max-w-6xl lg:grid-cols-[1.08fr_0.92fr]">
            <div class="flex flex-col justify-between px-4 py-10 sm:px-6 sm:py-14 lg:border-r lg:border-line lg:px-8 lg:py-16">
                <div class="max-w-xl space-y-5">
                    <p class="inline-flex items-center gap-2 text-xs font-medium text-brand">
                        <span class="h-1.5 w-1.5 rounded-full bg-brand"></span>
                        Program terverifikasi · Laporan penyaluran terbuka
                    </p>
                    <h1 class="font-display text-4xl font-semibold leading-[1.08] text-ink sm:text-5xl">
                        Salurkan kebaikan ke program yang dampaknya tercatat jelas.
                    </h1>
                    <p class="text-sm leading-7 text-muted sm:text-base">
                        Setiap donasi tercatat otomatis dengan kode transfer unik, kwitansi PDF instan, dan pembaruan progres langsung dari lapangan tanpa perlu membuat akun.
                    </p>

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a
                            href="#daftar-program"
                            class="inline-flex items-center rounded-lg bg-brand px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-deep"
                        >
                            Jelajahi program aktif
                        </a>
                        <a
                            href="{{ route('donation.lookup') }}"
                            class="inline-flex items-center rounded-lg border border-line-strong bg-surface px-5 py-2.5 text-sm font-medium text-ink transition-colors hover:bg-surface-subtle"
                        >
                            Lacak nomor referensi
                        </a>
                    </div>
                </div>

                <div class="mt-10 grid grid-cols-2 gap-6 border-t border-line pt-7 sm:grid-cols-3 tabular-nums">
                    <div>
                        <p class="font-display text-2xl font-semibold text-ink sm:text-3xl">{{ $impact['value'] }}</p>
                        <p class="mt-1 text-xs text-muted">{{ $impact['label'] }}</p>
                    </div>
                    <div>
                        <p class="font-display text-2xl font-semibold text-ink sm:text-3xl">{{ count($campaigns) }}</p>
                        <p class="mt-1 text-xs text-muted">Program terpublikasi</p>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <p class="font-display text-2xl font-semibold text-brand sm:text-3xl">100%</p>
                        <p class="mt-1 text-xs text-muted">Status dapat dicek publik</p>
                    </div>
                </div>
            </div>

            {{-- Right Featured Story Slider --}}
            <div class="relative flex flex-col justify-between bg-brand-deep text-white">
                <div id="hero-slides" class="flex-1">
                    @foreach ($heroSlides as $index => $slide)
                        <article
                            class="{{ $index === 0 ? 'flex' : 'hidden' }} h-full min-h-[360px] flex-col justify-between bg-cover bg-center p-6 sm:p-8 lg:min-h-[460px] lg:p-10"
                            data-hero-slide
                            @if (! empty($slide['image_url']))
                                style="background-image: linear-gradient(180deg, rgba(9,54,48,0.55) 0%, rgba(9,26,24,0.88) 100%), url('{{ $slide['image_url'] }}');"
                            @endif
                        >
                            <div class="flex items-center justify-between gap-3">
                                <span class="rounded-md bg-white/15 px-2.5 py-1 text-xs font-medium text-white backdrop-blur-[2px]">
                                    {{ $slide['eyebrow'] }}
                                </span>
                                @if (count($heroSlides) > 1)
                                    <span class="font-mono text-xs text-white/75 tabular-nums">
                                        {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }} / {{ str_pad((string) count($heroSlides), 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                @endif
                            </div>

                            <div class="my-8 space-y-3">
                                <h2 class="font-display text-3xl font-semibold leading-[1.1] text-white sm:text-4xl">
                                    {{ $slide['headline'] }}
                                </h2>
                                <p class="max-w-md text-sm leading-relaxed text-white/80">
                                    {{ $slide['description'] }}
                                </p>
                            </div>

                            <div class="flex items-center justify-between gap-4 border-t border-white/15 pt-5">
                                <a
                                    href="{{ $slide['url'] ?? route('campaign.index') }}"
                                    class="inline-flex items-center gap-2 rounded-lg bg-surface px-4 py-2 text-xs font-semibold text-ink transition-colors hover:bg-brand-soft hover:text-brand"
                                >
                                    {{ $slide['cta'] }}
                                </a>

                                @if (count($heroSlides) > 1)
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="flex h-8 w-8 items-center justify-center rounded-md border border-white/20 text-white transition-colors hover:bg-white/10"
                                            data-hero-prev
                                            aria-label="Cerita sebelumnya"
                                        >
                                            &lsaquo;
                                        </button>
                                        <div class="flex items-center gap-1.5 px-1">
                                            @foreach ($heroSlides as $dotIndex => $dotSlide)
                                                <button
                                                    type="button"
                                                    class="h-1.5 w-1.5 rounded-full bg-white/40 transition-all {{ $dotIndex === $index ? 'w-4 bg-white' : '' }}"
                                                    data-hero-dot
                                                    data-slide-index="{{ $dotIndex }}"
                                                    aria-label="Slide {{ $dotIndex + 1 }}"
                                                ></button>
                                            @endforeach
                                        </div>
                                        <button
                                            type="button"
                                            class="flex h-8 w-8 items-center justify-center rounded-md border border-white/20 text-white transition-colors hover:bg-white/10"
                                            data-hero-next
                                            aria-label="Cerita berikutnya"
                                        >
                                            &rsaquo;
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Campaign Directory Section --}}
    <section id="daftar-program" class="border-b border-line bg-surface py-12 sm:py-16">
        <div class="mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-5 border-b border-line pb-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-xl">
                    <h2 class="font-display text-3xl font-semibold leading-tight text-ink sm:text-[2.25rem]">
                        Program yang membutuhkan dukungan
                    </h2>
                    <p class="mt-2 text-sm text-muted">
                        Saring berdasarkan kategori atau status untuk memilih program yang ingin Anda bantu hari ini.
                    </p>
                </div>

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

            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3" id="campaign-grid">
                @forelse ($campaigns as $campaign)
                    @include('pages.partials.campaign-card', ['campaign' => $campaign])
                @empty
                    <div class="col-span-full rounded-xl border border-dashed border-line-strong bg-canvas px-6 py-12 text-center text-sm text-muted">
                        Belum ada campaign yang dipublikasikan.
                    </div>
                @endforelse
            </div>

            <div class="mt-10 flex justify-center">
                <a
                    href="{{ route('campaign.index') }}"
                    class="inline-flex items-center rounded-lg border border-line-strong bg-surface px-5 py-2.5 text-sm font-medium text-ink transition-colors hover:bg-surface-subtle"
                >
                    Lihat semua program
                </a>
            </div>
        </div>
    </section>

    {{-- Editorial About Strip --}}
    <section class="border-b border-line bg-canvas py-12 sm:py-16">
        <div class="mx-auto grid w-full max-w-6xl gap-8 px-4 sm:px-6 lg:grid-cols-[1.2fr_0.8fr] lg:items-center lg:px-8">
            <div class="space-y-4">
                <p class="text-xs font-medium text-brand">Tentang platform</p>
                <h2 class="font-display text-3xl font-semibold leading-tight text-ink sm:text-4xl">
                    {{ $aboutPage['title'] ?? 'Tentang DonasiFlow Nusantara' }}
                </h2>
                <p class="max-w-2xl text-sm leading-7 text-muted sm:text-base">
                    {{ $aboutPage['intro'] ?? 'Kami mengelola penghimpunan dana sosial dengan pencatatan transaksi yang rapi, verifikasi cepat, dan laporan progres yang dapat dipantau langsung oleh donatur.' }}
                </p>
                <div class="pt-1">
                    <a
                        href="{{ route('pages.show', ['slug' => 'about']) }}"
                        class="inline-flex items-center rounded-lg bg-brand px-4 py-2.5 text-xs font-semibold text-white transition-colors hover:bg-brand-deep"
                    >
                        Baca profil lengkap
                    </a>
                </div>
            </div>

            <div class="rounded-xl border border-line bg-surface p-6 sm:p-8">
                <dl class="space-y-5 divide-y divide-line">
                    <div class="flex items-baseline justify-between gap-4">
                        <dt class="text-xs text-muted">Akumulasi dampak</dt>
                        <dd class="font-display text-3xl font-semibold text-brand tabular-nums">{{ $impact['value'] }}</dd>
                    </div>
                    <div class="pt-5">
                        <dt class="text-sm font-semibold text-ink">Verifikasi kode unik</dt>
                        <dd class="mt-1 text-xs leading-relaxed text-muted">Setiap transaksi transfer manual mendapatkan kode nominal unik sehingga pencocokan mutasi bank lebih akurat.</dd>
                    </div>
                    <div class="pt-5">
                        <dt class="text-sm font-semibold text-ink">Bukti &amp; status instan</dt>
                        <dd class="mt-1 text-xs leading-relaxed text-muted">Donatur bisa memantau status pembayaran dan mengunduh invoice PDF kapan saja memakai nomor referensi.</dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>

    {{-- Testimonials & FAQ Section --}}
    <section class="bg-surface py-12 sm:py-16">
        <div class="mx-auto grid w-full max-w-6xl gap-10 px-4 sm:px-6 lg:px-8 {{ count($testimonials) > 0 ? 'lg:grid-cols-[1.05fr_0.95fr]' : 'max-w-3xl' }}">
            @if (count($testimonials) > 0)
                <div class="space-y-6">
                    <div class="flex items-end justify-between gap-4 border-b border-line pb-4">
                        <div>
                            <h2 class="font-display text-2xl font-semibold text-ink sm:text-3xl">Cerita dari para donatur</h2>
                            <p class="mt-1 text-xs text-muted">Pengalaman langsung dari mereka yang ikut berdonasi.</p>
                        </div>
                        <a href="{{ route('pages.show', ['slug' => 'contact']) }}" class="text-xs font-semibold text-brand hover:underline">
                            Hubungi tim
                        </a>
                    </div>

                    <div class="space-y-4">
                        @foreach ($testimonials as $testimonial)
                            <article class="rounded-xl border border-line bg-canvas p-5">
                                <p class="text-sm leading-7 text-ink-soft">"{{ $testimonial['quote'] }}"</p>
                                <div class="mt-4 flex items-center justify-between gap-3 border-t border-line pt-3">
                                    <div>
                                        <p class="text-xs font-semibold text-ink">{{ $testimonial['name'] }}</p>
                                        <p class="text-[11px] text-muted">{{ collect([$testimonial['role'], $testimonial['location']])->filter()->implode(' · ') }}</p>
                                    </div>
                                    <div class="flex items-center gap-0.5 text-xs text-ochre" aria-label="Rating {{ $testimonial['rating'] }} dari 5">
                                        @for ($i = 0; $i < $testimonial['rating']; $i++)
                                            <span>&#9733;</span>
                                        @endfor
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="space-y-6">
                <div class="flex items-end justify-between gap-4 border-b border-line pb-4">
                    <div>
                        <h2 class="font-display text-2xl font-semibold text-ink sm:text-3xl">Pertanyaan umum</h2>
                        <p class="mt-1 text-xs text-muted">Hal yang paling sering ditanyakan seputar donasi.</p>
                    </div>
                    <a href="{{ route('pages.show', ['slug' => 'faq']) }}" class="text-xs font-semibold text-brand hover:underline">
                        Semua FAQ
                    </a>
                </div>

                <div class="divide-y divide-line rounded-xl border border-line bg-canvas">
                    @forelse (collect($faqs)->take(4) as $faq)
                        <article class="p-5">
                            <h3 class="text-sm font-semibold text-ink">{{ $faq['question'] }}</h3>
                            <p class="mt-2 text-xs leading-6 text-muted sm:text-sm">{{ $faq['answer'] }}</p>
                        </article>
                    @empty
                        <div class="p-6 text-sm text-muted">
                            Belum ada FAQ aktif.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (() => {
            const slides = Array.from(document.querySelectorAll('[data-hero-slide]'));
            const prevButtons = Array.from(document.querySelectorAll('[data-hero-prev]'));
            const nextButtons = Array.from(document.querySelectorAll('[data-hero-next]'));
            const dots = Array.from(document.querySelectorAll('[data-hero-dot]'));
            const filterButtons = Array.from(document.querySelectorAll('[data-filter]'));
            const campaignCards = Array.from(document.querySelectorAll('#campaign-grid [data-category]'));

            let activeSlide = 0;
            let autoplay;

            const setSlide = (index) => {
                if (!slides.length) return;
                activeSlide = (index + slides.length) % slides.length;

                slides.forEach((slide, slideIndex) => {
                    slide.classList.toggle('hidden', slideIndex !== activeSlide);
                    slide.classList.toggle('flex', slideIndex === activeSlide);
                });

                dots.forEach((dot) => {
                    const isCurrent = Number(dot.dataset.slideIndex) === activeSlide;
                    dot.classList.toggle('bg-white', isCurrent);
                    dot.classList.toggle('w-4', isCurrent);
                    dot.classList.toggle('bg-white/40', !isCurrent);
                    dot.classList.toggle('w-1.5', !isCurrent);
                });
            };

            const resetAutoplay = () => {
                if (slides.length <= 1) return;
                window.clearInterval(autoplay);
                autoplay = window.setInterval(() => setSlide(activeSlide + 1), 6500);
            };

            dots.forEach((dot) => {
                dot.addEventListener('click', () => {
                    setSlide(Number(dot.dataset.slideIndex));
                    resetAutoplay();
                });
            });

            prevButtons.forEach((btn) => btn.addEventListener('click', () => {
                setSlide(activeSlide - 1);
                resetAutoplay();
            }));

            nextButtons.forEach((btn) => btn.addEventListener('click', () => {
                setSlide(activeSlide + 1);
                resetAutoplay();
            }));

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

            setSlide(0);
            resetAutoplay();
        })();
    </script>
@endpush
