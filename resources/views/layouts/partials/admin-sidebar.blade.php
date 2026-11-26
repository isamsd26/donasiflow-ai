{{-- Mobile Drawer Sidebar --}}
<aside
    class="fixed inset-y-0 left-0 z-50 flex w-full max-w-[280px] -translate-x-full flex-col overflow-y-auto bg-[#0D2B27] px-4 py-5 text-[#D8E2DF] transition-transform duration-200 lg:hidden"
    data-admin-sidebar
>
    <div class="flex items-center justify-between gap-3 border-b border-white/10 pb-4">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-ochre font-display text-base font-semibold text-white">
                {{ strtoupper(substr($admin['brand'] ?? 'D', 0, 1)) }}
            </span>
            <div class="min-w-0">
                <p class="truncate text-sm font-bold leading-none text-white">{{ $admin['brand'] }}</p>
                <p class="mt-1 text-[11px] text-[#8FA8A2]">Panel pengelola</p>
            </div>
        </a>

        <button
            type="button"
            class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-white/15 text-[#A7BDB7] hover:bg-white/10 hover:text-white"
            data-admin-sidebar-close
            aria-label="Tutup menu"
        >
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path d="M5 5l10 10M15 5L5 15"></path>
            </svg>
        </button>
    </div>

    <nav class="mt-5 flex-1 space-y-4">
        @foreach ($admin['navigation'] as $index => $group)
            <section class="border-b border-white/8 pb-4 last:border-b-0">
                <button
                    type="button"
                    class="flex w-full items-center justify-between gap-2 px-2 text-left"
                    data-sidebar-group-toggle
                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                >
                    <span class="text-xs font-medium text-[#8FA8A2]">{{ $group['section'] }}</span>
                    <svg
                        class="h-4 w-4 shrink-0 text-[#8FA8A2] transition-transform {{ $index === 0 ? 'rotate-180' : '' }}"
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

                <div class="mt-2 space-y-1 {{ $index === 0 ? '' : 'hidden' }}" data-sidebar-group-panel>
                    @foreach ($group['items'] as $item)
                        @php
                            $isActive = request()->routeIs($item['active'] ?? $item['route']);
                        @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            data-admin-sidebar-link
                            class="relative flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-xs font-medium transition-colors {{ $isActive ? 'bg-white/12 text-white font-semibold' : 'text-[#B9CCC7] hover:bg-white/7 hover:text-white' }}"
                        >
                            @if ($isActive)
                                <span class="absolute left-0 top-1.5 bottom-1.5 w-[3px] rounded-r bg-ochre"></span>
                            @endif
                            @include('layouts.partials.admin-icon', ['icon' => $item['icon'], 'class' => 'h-4 w-4 shrink-0'])
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endforeach
    </nav>

    <div class="mt-4 border-t border-white/10 pt-4">
        <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-between rounded-lg border border-white/12 bg-white/5 px-3 py-2 text-xs font-medium text-[#C8D6D2] hover:bg-white/10 hover:text-white">
            <span>Lihat situs publik</span>
            <span>&#8599;</span>
        </a>
    </div>
</aside>

{{-- Desktop Deep Forest Sidebar (Solid, Quiet, High-Contrast Anchor) --}}
<aside class="hidden bg-[#0D2B27] px-4 py-5 text-[#D8E2DF] lg:sticky lg:top-0 lg:flex lg:h-screen lg:flex-col lg:self-start lg:overflow-y-auto">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 border-b border-white/10 px-1 pb-4">
        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-ochre font-display text-base font-semibold text-white">
            {{ strtoupper(substr($admin['brand'] ?? 'D', 0, 1)) }}
        </span>
        <div class="min-w-0">
            <p class="truncate text-sm font-bold leading-none text-white">{{ $admin['brand'] }}</p>
            <p class="mt-1 text-[11px] text-[#8FA8A2]">Panel pengelola</p>
        </div>
    </a>

    <nav class="mt-5 flex-1 space-y-5">
        @foreach ($admin['navigation'] as $group)
            <section>
                <p class="mb-1.5 px-2.5 text-[11px] font-medium text-[#7F9B94]">{{ $group['section'] }}</p>

                <div class="space-y-0.5">
                    @foreach ($group['items'] as $item)
                        @php
                            $isActive = request()->routeIs($item['active'] ?? $item['route']);
                        @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            class="relative flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-[13px] font-medium transition-colors {{ $isActive ? 'bg-white/12 text-white font-semibold' : 'text-[#B9CCC7] hover:bg-white/7 hover:text-white' }}"
                        >
                            @if ($isActive)
                                <span class="absolute left-0 top-1.5 bottom-1.5 w-[3px] rounded-r bg-ochre"></span>
                            @endif
                            @include('layouts.partials.admin-icon', ['icon' => $item['icon'], 'class' => 'h-4 w-4 shrink-0'])
                            <span class="truncate">{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endforeach
    </nav>

    <div class="mt-6 border-t border-white/10 pt-4">
        <a
            href="{{ route('home') }}"
            target="_blank"
            class="flex items-center justify-between rounded-lg border border-white/12 bg-white/5 px-3 py-2 text-xs font-medium text-[#C8D6D2] transition-colors hover:bg-white/10 hover:text-white"
        >
            <span>Lihat situs publik</span>
            <span aria-hidden="true">&#8599;</span>
        </a>
    </div>
</aside>
