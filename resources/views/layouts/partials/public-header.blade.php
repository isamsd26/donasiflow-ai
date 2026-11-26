<header class="sticky top-0 z-40 border-b border-line bg-surface/95 backdrop-blur-sm">
    <div class="mx-auto flex w-full max-w-6xl flex-wrap items-center justify-between gap-4 px-4 py-3.5 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brand font-display text-lg font-semibold text-white transition-colors group-hover:bg-brand-deep">
                {{ strtoupper(substr($site['name'] ?? 'D', 0, 1)) }}
            </span>
            <div class="min-w-0">
                <span class="block font-display text-xl font-semibold leading-none text-ink">
                    {{ $site['name'] ?? 'DonasiFlow Nusantara' }}
                </span>
                <span class="mt-0.5 block truncate text-[11px] text-muted">
                    {{ $site['tagline'] ?? 'Penghimpunan dana dan pelaporan transparan' }}
                </span>
            </div>
        </a>

        <nav class="flex flex-wrap items-center gap-1.5 sm:gap-2 text-xs sm:text-[13px]" aria-label="Navigasi utama">
            @foreach ($navigation ?? [] as $item)
                @php
                    $isCurrent = request()->routeIs($item['route']) && empty($item['params'])
                        || (isset($item['params']['slug']) && request()->route('slug') === $item['params']['slug']);
                @endphp
                <a
                    href="{{ route($item['route'], $item['params'] ?? []) }}"
                    class="rounded-md px-3 py-1.5 font-medium transition-colors {{ $isCurrent ? 'bg-brand-soft text-brand font-semibold' : 'text-muted hover:bg-surface-subtle hover:text-ink' }}"
                >
                    {{ $item['label'] }}
                </a>
            @endforeach

            <a
                href="{{ route('donation.lookup') }}"
                class="ml-1 inline-flex items-center rounded-lg border border-line-strong bg-surface px-3.5 py-1.5 font-medium text-ink transition-colors hover:bg-surface-subtle"
            >
                Cek status
            </a>
            <a
                href="{{ route('campaign.index') }}"
                class="inline-flex items-center rounded-lg bg-brand px-3.5 py-1.5 font-semibold text-white transition-colors hover:bg-brand-deep"
            >
                Donasi sekarang
            </a>
        </nav>
    </div>
</header>
