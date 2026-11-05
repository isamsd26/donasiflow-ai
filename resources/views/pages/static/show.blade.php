@extends('layouts.public')

@section('content')
    @php
        $paragraphs = collect(preg_split("/\r\n|\n|\r/", trim((string) ($page['content'] ?? ''))))
            ->map(fn ($paragraph) => trim($paragraph))
            ->filter()
            ->values();
    @endphp

    <div class="mx-auto w-[min(1120px,calc(100%-1rem))] space-y-5">
        <section class="rounded-[24px] bg-[linear-gradient(135deg,#1e293b,#059669)] px-5 py-6 text-white shadow-[0_18px_40px_rgba(15,23,42,0.16)] sm:rounded-[30px] sm:px-8 sm:py-8">
            <p class="text-[11px] uppercase tracking-[0.28em] text-emerald-100">{{ $page['eyebrow'] }}</p>
            <h1 class="mt-3 font-display text-[2.55rem] leading-none sm:text-[3.4rem]">{{ $page['title'] }}</h1>
            <p class="mt-4 max-w-3xl text-sm leading-7 text-emerald-50/90">{{ $page['intro'] }}</p>
        </section>

        <section class="grid gap-5 lg:grid-cols-[0.85fr_1.15fr]">
            <aside class="rounded-[24px] bg-white px-4 py-5 shadow-[0_16px_34px_rgba(15,23,42,0.08)] sm:rounded-[28px] sm:px-5 sm:py-6">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Navigasi Publik</p>
                <div class="mt-4 space-y-2">
                    @foreach ($navigation as $item)
                        <a
                            href="{{ route($item['route'], $item['params'] ?? []) }}"
                            class="flex items-center justify-between rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm font-medium text-[#334155] hover:border-pm-blue hover:text-pm-blue"
                        >
                            <span>{{ $item['label'] }}</span>
                            <span>&rsaquo;</span>
                        </a>
                    @endforeach
                </div>
            </aside>

            <div class="space-y-4">
                <article class="rounded-[24px] bg-white px-4 py-5 shadow-[0_16px_34px_rgba(15,23,42,0.08)] sm:rounded-[28px] sm:px-5 sm:py-6">
                    <h2 class="font-display text-[2rem] leading-none text-[#1e293b] sm:text-3xl">{{ $page['title'] }}</h2>
                    @if ($page['slug'] === 'faq' && ! empty($page['faq_items']))
                        <div class="mt-5 space-y-3">
                            @foreach ($page['faq_items'] as $item)
                                <article class="rounded-[22px] border border-[#e2e8f0] bg-[#ffffff] px-4 py-4">
                                    <h3 class="text-base font-semibold text-[#1e293b]">{{ $item['question'] }}</h3>
                                    <p class="mt-2 text-sm leading-7 text-[#334155]">{{ $item['answer'] }}</p>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="mt-4 space-y-4 text-sm leading-7 text-[#334155]">
                            @forelse ($paragraphs as $paragraph)
                                <p>{{ $paragraph }}</p>
                            @empty
                                <p>Konten halaman ini belum diisi dari CMS.</p>
                            @endforelse
                        </div>
                    @endif
                </article>

                @if ($page['slug'] === 'contact' && ! empty($page['contact_cards']))
                    <article class="rounded-[24px] bg-white px-4 py-5 shadow-[0_16px_34px_rgba(15,23,42,0.08)] sm:rounded-[28px] sm:px-5 sm:py-6">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Kontak Terstruktur</p>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            @foreach ($page['contact_cards'] as $item)
                                <div class="rounded-2xl border border-[#e2e8f0] bg-[#ffffff] px-4 py-4">
                                    <p class="text-[10px] uppercase tracking-[0.14em] text-pm-muted">{{ $item['label'] }}</p>
                                    <p class="mt-2 text-sm font-semibold leading-6 text-[#1e293b]">{{ $item['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endif
            </div>
        </section>
    </div>
@endsection
