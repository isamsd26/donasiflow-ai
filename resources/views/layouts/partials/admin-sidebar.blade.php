<aside
    class="fixed inset-y-0 left-0 z-50 flex w-full max-w-[320px] -translate-x-full flex-col overflow-y-auto bg-[linear-gradient(180deg,#1e293b,#0f172a)] px-4 py-5 text-white shadow-[0_24px_70px_rgba(15,23,42,0.28)] transition-transform duration-200 lg:hidden"
    data-admin-sidebar
>
    <div class="flex items-start justify-between gap-4">
        <div class="rounded-[22px] border border-white/10 bg-white/8 px-4 py-4">
            <p class="font-display text-3xl leading-none">{{ $admin['brand'] }}</p>
            <p class="mt-2 text-xs uppercase tracking-[0.22em] text-emerald-100">CMS</p>
        </div>

        <button
            type="button"
            class="mt-2 inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/8 text-white/90 transition hover:bg-white/14"
            data-admin-sidebar-close
            aria-label="Tutup menu"
        >
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path d="M5 5l10 10M15 5L5 15"></path>
            </svg>
        </button>
    </div>

    <nav class="mt-5 space-y-4">
        @foreach ($admin['navigation'] as $index => $group)
            <section class="rounded-[24px] border border-white/8 bg-white/5 px-3 py-3">
                <button
                    type="button"
                    class="flex w-full items-start justify-between gap-3 text-left"
                    data-sidebar-group-toggle
                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                >
                    <div class="min-w-0">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-emerald-100">{{ $group['section'] }}</p>
                        <p class="mt-2 text-sm leading-6 text-emerald-50/80">{{ $group['description'] }}</p>
                    </div>

                    <svg
                        class="mt-1 h-4 w-4 shrink-0 transition-transform {{ $index === 0 ? 'rotate-180' : '' }}"
                        viewBox="0 0 20 20"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                        data-sidebar-chevron
                    >
                        <path d="m5 8 5 5 5-5"></path>
                    </svg>
                </button>

                <div class="mt-3 space-y-2 {{ $index === 0 ? '' : 'hidden' }}" data-sidebar-group-panel>
                    @foreach ($group['items'] as $item)
                        @php
                            $isActive = request()->routeIs($item['active'] ?? $item['route']);
                        @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            data-admin-sidebar-link
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ $isActive ? 'bg-white text-[#1e293b] shadow-[0_10px_18px_rgba(255,255,255,0.08)]' : 'text-emerald-50/90 hover:bg-white/10 hover:text-white' }}"
                        >
                            @include('layouts.partials.admin-icon', ['icon' => $item['icon'], 'class' => 'h-5 w-5'])
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endforeach
    </nav>
</aside>

<aside
    class="hidden bg-[linear-gradient(180deg,#1e293b,#0f172a)] px-4 py-5 text-white lg:sticky lg:top-0 lg:block lg:self-start lg:h-screen lg:overflow-y-auto lg:px-5"
>
    <div class="rounded-[22px] border border-white/10 bg-white/8 px-4 py-4">
        <p class="font-display text-3xl leading-none">{{ $admin['brand'] }}</p>
        <p class="mt-2 text-xs uppercase tracking-[0.22em] text-emerald-100">CMS</p>
    </div>

    <nav class="mt-5 space-y-5">
        @foreach ($admin['navigation'] as $group)
            <section>
                <div class="mb-2 px-2">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-emerald-100">{{ $group['section'] }}</p>
                </div>

                <div class="space-y-2">
                    @foreach ($group['items'] as $item)
                        @php
                            $isActive = request()->routeIs($item['active'] ?? $item['route']);
                        @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ $isActive ? 'bg-white text-[#1e293b] shadow-[0_10px_18px_rgba(255,255,255,0.08)]' : 'text-emerald-50/90 hover:bg-white/10 hover:text-white' }}"
                        >
                            @include('layouts.partials.admin-icon', ['icon' => $item['icon'], 'class' => 'h-5 w-5'])
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endforeach
    </nav>
</aside>
