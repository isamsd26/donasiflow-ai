<article
    class="relative overflow-hidden rounded-2xl border border-[#e2e8f0] bg-[linear-gradient(180deg,#ffffff,#ffffff)] shadow-[0_10px_22px_rgba(15,23,42,0.06)] transition duration-150 hover:-translate-y-[3px] group"
    style="cursor: pointer;"
    onclick="window.location.href='{{ route('campaign.show', ['slug' => $campaign['slug']]) }}'"
    data-category="{{ $campaign['category'] }}"
    data-status="{{ $campaign['status'] }}"
>
    
    <div class="relative aspect-[1.28/1] overflow-hidden bg-pm-blue" style="background: {{ $campaign['cover'] }};">
        <div class="absolute inset-0 opacity-30 mix-blend-soft-light" style="background-image: radial-gradient(circle at top right, rgba(255,255,255,0.5), transparent 28%), linear-gradient(135deg, rgba(255,255,255,0.12) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.08) 50%, rgba(255,255,255,0.08) 75%, transparent 75%, transparent); background-size: auto, 22px 22px;"></div>
        <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(3,19,37,0.04),rgba(3,19,37,0.18))]"></div>
        <div class="relative flex h-full flex-col justify-between p-4 text-white">
            <div class="flex items-start justify-between gap-3">
                <span class="rounded-full border border-white/20 bg-white/12 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.18em]">{{ $campaign['badge'] }}</span>
                <span class="rounded-full bg-black/20 px-2.5 py-1 text-[10px] font-medium">{{ $campaign['days_left'] }}</span>
            </div>

            <h3 class="max-w-[82%] font-display text-[1.9rem] leading-[0.92] tracking-tight text-white sm:text-[2.2rem]">
                {{ $campaign['poster_title'] }}
            </h3>
        </div>
    </div>

    <div class="px-4 pb-4 pt-3">
        <div class="flex items-start justify-between gap-3">
            <h3 class="text-[13px] font-medium text-pm-text group-hover:text-pm-blue transition-colors">
                {{ $campaign['title'] }}
            </h3>
            <span class="rounded-full bg-pm-blue-soft px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.08em] text-pm-blue">{{ $campaign['status_label'] }}</span>
        </div>
        <p class="mt-1 text-[11px] text-[#059669]">{{ $campaign['organizer'] }}</p>

        <div class="mt-2 border-t border-[#e2e8f0] pt-2">
            <div class="flex items-center justify-between text-[10px] text-pm-muted">
                <span>Terkumpul</span>
                <span>{{ $campaign['days_left_short'] }}</span>
            </div>
            <div class="mt-1 text-[13px] font-semibold text-pm-blue">{{ $campaign['collected'] }}</div>
            <div class="mt-2 h-[3px] rounded-full bg-[#e2e8f0]">
                <div class="h-full rounded-full bg-pm-blue" style="width: {{ $campaign['progress'] }}%"></div>
            </div>
        </div>

        <div class="mt-3 flex items-center gap-2" style="position: relative; z-index: 20;" onclick="event.stopPropagation()">
            <a
                href="{{ $campaign['status'] === 'active' ? route('donation.create', ['slug' => $campaign['slug']]) : route('campaign.show', ['slug' => $campaign['slug']]) }}"
                class="flex h-8 flex-1 items-center justify-center rounded-full bg-pm-blue text-[11px] font-medium text-white"
            >
                {{ $campaign['cta'] }}
            </a>
            <button type="button" class="flex h-8 w-8 items-center justify-center rounded-full border border-[#e2e8f0] text-[11px] text-pm-muted">
                +
            </button>
        </div>
    </div>
</article>
