@extends('layouts.public')

@section('content')
    @php
        $paragraphs = collect(preg_split("/\r\n|\n|\r/", trim((string) ($page['content'] ?? ''))))
            ->map(fn ($paragraph) => trim($paragraph))
            ->filter()
            ->values();
    @endphp

    <section class="border-b border-line bg-canvas py-10 sm:py-12">
        <div class="mx-auto w-full max-w-5xl px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-medium text-brand">{{ $page['eyebrow'] }}</p>
            <h1 class="mt-2 font-display text-3xl font-semibold leading-tight text-ink sm:text-5xl">{{ $page['title'] }}</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-muted sm:text-base">{{ $page['intro'] }}</p>
        </div>
    </section>

    <section class="bg-surface py-10 sm:py-12">
        <div class="mx-auto grid w-full max-w-5xl gap-8 px-4 sm:px-6 lg:grid-cols-[220px_1fr] lg:items-start lg:px-8">
            <aside class="rounded-xl border border-line bg-canvas p-4">
                <p class="px-2 text-xs font-semibold text-ink">Halaman lainnya</p>
                <nav class="mt-3 space-y-1">
                    @foreach ($navigation as $item)
                        <a
                            href="{{ route($item['route'], $item['params'] ?? []) }}"
                            class="flex items-center justify-between rounded-md px-2.5 py-2 text-xs font-medium text-muted transition-colors hover:bg-surface hover:text-ink"
                        >
                            <span>{{ $item['label'] }}</span>
                            <span>&rsaquo;</span>
                        </a>
                    @endforeach
                </nav>
            </aside>

            <div class="space-y-6">
                <article class="rounded-xl border border-line bg-surface p-6 sm:p-8">
                    @if ($page['slug'] === 'faq' && ! empty($page['faq_items']))
                        <div class="divide-y divide-line">
                            @foreach ($page['faq_items'] as $item)
                                <div class="py-5 first:pt-0 last:pb-0">
                                    <h2 class="text-base font-semibold text-ink">{{ $item['question'] }}</h2>
                                    <p class="mt-2 text-sm leading-7 text-muted">{{ $item['answer'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="max-w-2xl space-y-4 text-sm leading-7 text-ink-soft sm:text-base">
                            @forelse ($paragraphs as $paragraph)
                                <p>{{ $paragraph }}</p>
                            @empty
                                <p class="text-muted">Konten halaman ini belum diisi.</p>
                            @endforelse
                        </div>
                    @endif
                </article>

                @if ($page['slug'] === 'contact' && ! empty($page['contact_cards']))
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ($page['contact_cards'] as $item)
                            <div class="rounded-xl border border-line bg-canvas p-5">
                                <p class="text-xs text-muted">{{ $item['label'] }}</p>
                                <p class="mt-1.5 text-sm font-semibold leading-6 text-ink">{{ $item['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
