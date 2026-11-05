<header class="sticky top-0 z-30 border-b border-[#dbe5f0] bg-white/90 px-4 py-4 backdrop-blur sm:px-6 lg:px-8">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <button
                type="button"
                class="mb-3 inline-flex items-center gap-2 rounded-full border border-[#e2e8f0] bg-white px-3 py-2 text-sm font-medium text-[#334155] shadow-[0_8px_18px_rgba(15,23,42,0.06)] lg:hidden"
                data-admin-sidebar-open
            >
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M3 5h14M3 10h14M3 15h14"></path>
                </svg>
                Menu
            </button>
            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">CMS Admin</p>
            <h1 class="mt-1 font-display text-[2rem] leading-none text-[#1e293b]">{{ $page['title'] }}</h1>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="rounded-full border border-[#e2e8f0] bg-[#f8fafc] px-4 py-2 text-sm text-[#33506f]">
                {{ $page['description'] ?? 'Halaman administrasi' }}
            </div>
            <div class="flex items-center gap-3 rounded-full bg-[#1e293b] px-3 py-2 text-white">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/15 text-sm font-semibold">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                </span>
                <span class="text-sm font-medium">{{ auth()->user()?->name ?? 'Admin' }}</span>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="rounded-full border border-[#e2e8f0] bg-white px-4 py-2 text-sm font-medium text-[#334155]">
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>
