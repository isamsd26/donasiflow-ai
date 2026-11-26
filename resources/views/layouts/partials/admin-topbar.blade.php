<header class="sticky top-0 z-30 border-b border-line bg-surface/95 px-4 py-3.5 backdrop-blur-sm sm:px-6 lg:px-8">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
            <button
                type="button"
                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-line bg-canvas text-ink lg:hidden"
                data-admin-sidebar-open
                aria-label="Buka menu"
            >
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M3 5h14M3 10h14M3 15h14"></path>
                </svg>
            </button>

            <div class="min-w-0">
                <h1 class="truncate font-display text-2xl font-semibold leading-none text-ink">{{ $page['title'] }}</h1>
                @if (! empty($page['description']))
                    <p class="mt-1 hidden truncate text-xs text-muted sm:block">{{ $page['description'] }}</p>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <div class="hidden items-center gap-2 rounded-lg border border-line bg-canvas px-3 py-1.5 sm:flex">
                <span class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-brand text-xs font-semibold text-white">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                </span>
                <span class="text-xs font-medium text-ink">{{ auth()->user()?->name ?? 'Admin' }}</span>
            </div>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button
                    type="submit"
                    class="rounded-lg border border-line-strong bg-surface px-3.5 py-1.5 text-xs font-medium text-ink-soft transition-colors hover:bg-surface-subtle hover:text-ink"
                >
                    Keluar
                </button>
            </form>
        </div>
    </div>
</header>
