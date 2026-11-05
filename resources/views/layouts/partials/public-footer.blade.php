@php
    $footer = $footer ?? \App\Support\PublicSite::footer();
@endphp

<footer class="mx-auto mt-8 w-[min(1120px,calc(100%-1rem))] pb-6">
    <div class="overflow-hidden rounded-[24px] bg-[linear-gradient(135deg,#1e293b,#059669)] text-white shadow-[0_18px_40px_rgba(15,23,42,0.16)]">
        <div class="grid gap-6 px-5 py-6 sm:px-6 sm:py-7 lg:grid-cols-[1.1fr_0.75fr_0.75fr]">
            <div>
                <p class="font-display text-[2rem] leading-none">{{ $site['name'] ?? 'DonasiFlow Nusantara' }}</p>
                <p class="mt-3 max-w-md text-sm leading-7 text-emerald-50/90">{{ $footer['description'] }}</p>

                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ $footer['cta']['url'] }}" class="inline-flex items-center rounded-full bg-white px-4 py-2 text-xs font-semibold text-pm-blue transition hover:bg-emerald-50">
                        {{ $footer['cta']['label'] }}
                    </a>
                    <a href="{{ route('pages.show', ['slug' => 'contact']) }}" class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold text-white">
                        Hubungi Kami
                    </a>
                </div>

                <div class="mt-5 flex flex-wrap items-center gap-2">
                    @foreach ($socialLinks ?? [] as $link)
                        @if ($link['label'] !== 'Hubungi Kami')
                            <a
                                href="{{ $link['url'] }}"
                                aria-label="{{ $link['label'] }}"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/12 bg-white/10 text-[15px] text-white transition hover:bg-white hover:text-pm-blue"
                            >
                                @switch($link['icon'])
                                    @case('facebook')
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="M13.5 21v-7h2.3l.4-2.8h-2.7V9.4c0-.8.2-1.4 1.4-1.4H16V5.5c-.2 0-.9-.1-1.8-.1-1.8 0-3.1 1.1-3.1 3.2v2.6H9v2.8h2.3v7h2.2Z"/>
                                        </svg>
                                        @break
                                    @case('twitter')
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="M18.9 3H22l-6.8 7.8L23 21h-6.1l-4.8-6.2L6.7 21H3.6l7.3-8.4L3.4 3h6.2l4.3 5.7L18.9 3Zm-1.1 16h1.7L8.7 4.9H6.9L17.8 19Z"/>
                                        </svg>
                                        @break
                                    @case('instagram')
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <rect x="3.5" y="3.5" width="17" height="17" rx="4"></rect>
                                            <circle cx="12" cy="12" r="4"></circle>
                                            <circle cx="17.5" cy="6.5" r="1"></circle>
                                        </svg>
                                        @break
                                    @case('telegram')
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="M21.4 4.6c.3-.9-.4-1.7-1.3-1.4L3.7 9.1c-1 .4-.9 1.8.1 2l4.3 1.3 1.6 5.2c.2.8 1.2 1 1.8.4l2.4-2.5 4.7 3.4c.8.5 1.8.1 2-1l2.8-13.3ZM10 16.4l-1.2-4 8.4-5.4-6.1 6.5-.1 2.9-1 1Z"/>
                                        </svg>
                                        @break
                                @endswitch
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="rounded-[20px] border border-white/10 bg-white/8 px-4 py-4 backdrop-blur-sm">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-emerald-100">Navigasi Footer</p>
                <div class="mt-4 space-y-2">
                    @foreach ($footer['navigation'] as $item)
                        <a href="{{ $item['url'] }}" class="flex items-center justify-between rounded-2xl border border-white/10 bg-white/8 px-4 py-3 text-sm text-emerald-50 transition hover:bg-white hover:text-pm-blue">
                            <span>{{ $item['label'] }}</span>
                            <span>&rsaquo;</span>
                        </a>
                    @endforeach
                </div>

                <p class="mt-5 text-[10px] font-semibold uppercase tracking-[0.18em] text-emerald-100">Bantuan</p>
                <div class="mt-4 space-y-2">
                    @foreach ($footer['support'] as $item)
                        <a href="{{ $item['url'] }}" class="flex items-center justify-between rounded-2xl border border-white/10 bg-white/8 px-4 py-3 text-sm text-emerald-50 transition hover:bg-white hover:text-pm-blue">
                            <span>{{ $item['label'] }}</span>
                            <span>&rsaquo;</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="rounded-[20px] border border-white/10 bg-white/8 px-4 py-4 backdrop-blur-sm">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-emerald-100">Kontak</p>
                <div class="mt-4 space-y-3 text-sm leading-6 text-emerald-50/90">
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.16em] text-emerald-100">Email</p>
                        <p>{{ $footer['contact']['email'] }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.16em] text-emerald-100">WhatsApp</p>
                        <p>{{ $footer['contact']['whatsapp'] }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.16em] text-emerald-100">Alamat</p>
                        <p>{{ $footer['contact']['address'] }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.16em] text-emerald-100">Jam Layanan</p>
                        <p>{{ $footer['contact']['hours'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-white/10 px-5 py-4 text-xs text-emerald-100/90 sm:px-6">
            {{ $footer['copyright'] }}
        </div>
    </div>
</footer>
