@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <section class="rounded-xl border border-line bg-surface">
            <div class="border-b border-line px-5 py-5">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-brand">Pengaturan Sistem</p>
                <h2 class="mt-1 font-display text-3xl leading-none text-ink">{{ $page['title'] }}</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-muted">{{ $page['description'] }}</p>
            </div>

            <div class="grid gap-5 px-5 py-5 lg:grid-cols-2">
                @foreach ($page['sections'] as $section)
                    <article class="rounded-xl border border-line bg-[#ffffff] px-5 py-5">
                        <h3 class="font-display text-2xl leading-none text-ink">{{ $section['title'] }}</h3>
                        <div class="mt-4 space-y-3">
                            @foreach ($section['items'] as $item)
                                <div class="rounded-lg border border-[#e7eef6] bg-white px-4 py-3 text-sm text-ink-soft">
                                    {{ $item }}
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="border-t border-line px-5 py-5">
                <button type="button" class="rounded-full bg-brand px-5 py-3 text-sm font-semibold text-white">
                    Simpan Perubahan
                </button>
            </div>
        </section>
    </div>
@endsection
