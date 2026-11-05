<header class="mx-auto w-[min(1120px,calc(100%-1rem))]">
    <div class="rounded-[10px] bg-pm-blue px-4 py-3 text-white shadow-[0_10px_24px_rgba(15,23,42,0.16)] sm:px-6">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="text-center lg:text-left">
                <a href="{{ route('home') }}" class="font-display text-[2rem] leading-none">{{ $site['name'] ?? 'DonasiFlow Nusantara' }}</a>
                <p class="mt-1 text-[10px] uppercase tracking-[0.28em] text-emerald-100">{{ $site['tagline'] ?? 'Campaign stories for real-world impact' }}</p>
            </div>

            <nav class="flex flex-wrap items-center justify-center gap-2 text-xs">
                @foreach ($navigation ?? [] as $item)
                    <a
                        href="{{ route($item['route'], $item['params'] ?? []) }}"
                        class="rounded-full border border-white/18 bg-white/8 px-3 py-1.5 font-medium text-white transition hover:bg-white/14"
                    >
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
        </div>
    </div>
</header>
