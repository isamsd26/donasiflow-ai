@php
    $progressVal = (int) ($campaign['progress'] ?? 0);
    $isUrgent = $progressVal < 35 && ($campaign['status'] ?? '') === 'active';
@endphp

<article
    class="group flex flex-col overflow-hidden rounded-xl border border-line bg-surface transition-colors hover:border-line-strong"
    style="cursor: pointer;"
    onclick="window.location.href='{{ route('campaign.show', ['slug' => $campaign['slug']]) }}'"
    data-category="{{ $campaign['category'] }}"
    data-status="{{ $campaign['status'] }}"
>
    <div class="relative aspect-[16/10] overflow-hidden bg-brand-deep" style="background: {{ $campaign['cover'] }};">
        <div class="absolute inset-0 bg-gradient-to-t from-[#0B1418]/80 via-[#0B1418]/25 to-[#0B1418]/10"></div>
        <div class="relative flex h-full flex-col justify-between p-4 text-white">
            <div class="flex items-center justify-between gap-2 text-[11px]">
                <span class="rounded-md bg-black/35 px-2.5 py-1 font-medium backdrop-blur-[2px]">
                    {{ $campaign['badge'] }}
                </span>
                <span class="rounded-md bg-black/35 px-2.5 py-1 font-medium tabular-nums backdrop-blur-[2px] {{ $isUrgent ? 'text-[#F6D399]' : 'text-white/90' }}">
                    {{ $campaign['days_left'] }}
                </span>
            </div>

            <h3 class="max-w-[90%] font-display text-xl font-semibold leading-snug text-white sm:text-[1.35rem]">
                {{ $campaign['poster_title'] }}
            </h3>
        </div>
    </div>

    <div class="flex flex-1 flex-col justify-between p-4 sm:p-5">
        <div>
            <div class="flex items-start justify-between gap-2">
                <h4 class="text-sm font-semibold leading-snug text-ink transition-colors group-hover:text-brand">
                    {{ $campaign['title'] }}
                </h4>
                <span class="shrink-0 rounded-md px-2 py-0.5 text-[11px] font-medium {{ ($campaign['status'] ?? '') === 'active' ? 'bg-brand-soft text-brand' : 'bg-surface-subtle text-muted' }}">
                    {{ $campaign['status_label'] }}
                </span>
            </div>
            <p class="mt-1 text-xs text-muted">{{ $campaign['organizer'] }}</p>
        </div>

        <div class="mt-4 space-y-3 border-t border-line pt-3.5 tabular-nums">
            <div class="h-1.5 overflow-hidden rounded-full bg-surface-subtle">
                <div
                    class="h-full rounded-full {{ $isUrgent ? 'bg-ochre' : 'bg-brand' }}"
                    style="width: {{ min(100, max(0, $progressVal)) }}%"
                ></div>
            </div>

            <div class="flex items-baseline justify-between gap-2">
                <div>
                    <p class="text-[11px] text-muted">Terkumpul</p>
                    <p class="text-sm font-bold text-ink">{{ $campaign['collected'] }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[11px] text-muted">{{ $campaign['days_left_short'] }}</p>
                    <p class="text-xs font-semibold {{ $isUrgent ? 'text-ochre-ink' : 'text-brand' }}">{{ $progressVal }}%</p>
                </div>
            </div>

            <div class="pt-1" style="position: relative; z-index: 20;" onclick="event.stopPropagation()">
                <a
                    href="{{ $campaign['status'] === 'active' ? route('donation.create', ['slug' => $campaign['slug']]) : route('campaign.show', ['slug' => $campaign['slug']]) }}"
                    class="flex w-full items-center justify-center rounded-lg border border-line-strong bg-canvas px-3 py-2 text-xs font-semibold text-ink transition-colors hover:border-brand hover:bg-brand hover:text-white"
                >
                    {{ $campaign['cta'] }}
                </a>
            </div>
        </div>
    </div>
</article>
