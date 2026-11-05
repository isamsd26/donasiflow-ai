@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <section class="rounded-[26px] bg-white shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
            <div class="border-b border-[#e2e8f0] px-5 py-5">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Pengaturan Sistem</p>
                <h2 class="mt-1 font-display text-3xl leading-none text-[#1e293b]">{{ $page['title'] }}</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-[#64748b]">{{ $page['description'] }}</p>
            </div>

            <div class="grid gap-5 px-5 py-5 lg:grid-cols-2">
                @foreach ($page['sections'] as $section)
                    <article class="rounded-[24px] border border-[#e2e8f0] bg-[#ffffff] px-5 py-5">
                        <h3 class="font-display text-2xl leading-none text-[#1e293b]">{{ $section['title'] }}</h3>
                        <div class="mt-4 space-y-3">
                            @foreach ($section['items'] as $item)
                                <div class="rounded-2xl border border-[#e7eef6] bg-white px-4 py-3 text-sm text-[#334155]">
                                    {{ $item }}
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="border-t border-[#e2e8f0] px-5 py-5">
                <button type="button" class="rounded-full bg-pm-blue px-5 py-3 text-sm font-semibold text-white">
                    Simpan Perubahan
                </button>
            </div>
        </section>
    </div>
@endsection
