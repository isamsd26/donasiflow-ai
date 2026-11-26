@php
    $footer = $footer ?? \App\Support\PublicSite::footer();
@endphp

<footer class="mt-16 border-t border-line bg-ink text-[#D4D7DD]">
    <div class="mx-auto w-full max-w-6xl px-4 py-12 sm:px-6 lg:px-8 lg:py-14">
        <div class="grid gap-10 lg:grid-cols-[1.3fr_0.8fr_0.8fr_1fr]">
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand font-display text-base font-semibold text-white">
                        {{ strtoupper(substr($site['name'] ?? 'D', 0, 1)) }}
                    </span>
                    <p class="font-display text-xl font-semibold text-white">{{ $site['name'] ?? 'DonasiFlow Nusantara' }}</p>
                </div>
                <p class="max-w-sm text-sm leading-relaxed text-[#A3ABB7]">
                    {{ $footer['description'] }}
                </p>

                <div class="flex flex-wrap items-center gap-2.5 pt-1">
                    <a
                        href="{{ $footer['cta']['url'] }}"
                        class="inline-flex items-center rounded-lg bg-brand px-4 py-2 text-xs font-semibold text-white transition-colors hover:bg-brand-deep"
                    >
                        {{ $footer['cta']['label'] }}
                    </a>
                    <a
                        href="{{ route('pages.show', ['slug' => 'contact']) }}"
                        class="inline-flex items-center rounded-lg border border-white/15 px-4 py-2 text-xs font-medium text-white transition-colors hover:bg-white/10"
                    >
                        Hubungi kami
                    </a>
                </div>

                @if (! empty($socialLinks))
                    <div class="flex flex-wrap items-center gap-2 pt-2">
                        @foreach ($socialLinks as $link)
                            @if ($link['label'] !== 'Hubungi Kami')
                                <a
                                    href="{{ $link['url'] }}"
                                    aria-label="{{ $link['label'] }}"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-white/12 text-[#A3ABB7] transition-colors hover:border-white/30 hover:text-white"
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
                @endif
            </div>

            <div>
                <p class="text-xs font-semibold text-white">Jelajahi</p>
                <ul class="mt-3.5 space-y-2.5 text-sm text-[#A3ABB7]">
                    @foreach ($footer['navigation'] as $item)
                        <li>
                            <a href="{{ $item['url'] }}" class="transition-colors hover:text-white">{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <p class="text-xs font-semibold text-white">Bantuan</p>
                <ul class="mt-3.5 space-y-2.5 text-sm text-[#A3ABB7]">
                    @foreach ($footer['support'] as $item)
                        <li>
                            <a href="{{ $item['url'] }}" class="transition-colors hover:text-white">{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <p class="text-xs font-semibold text-white">Kontak layanan</p>
                <dl class="mt-3.5 space-y-2.5 text-sm text-[#A3ABB7]">
                    <div>
                        <dt class="text-[11px] text-[#788291]">Email</dt>
                        <dd class="text-[#D4D7DD]">{{ $footer['contact']['email'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] text-[#788291]">WhatsApp</dt>
                        <dd class="text-[#D4D7DD]">{{ $footer['contact']['whatsapp'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] text-[#788291]">Alamat</dt>
                        <dd class="leading-relaxed">{{ $footer['contact']['address'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] text-[#788291]">Jam layanan</dt>
                        <dd>{{ $footer['contact']['hours'] }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="mt-10 flex flex-col gap-2 border-t border-white/10 pt-6 text-xs text-[#788291] sm:flex-row sm:items-center sm:justify-between">
            <p>{{ $footer['copyright'] }}</p>
            <p>Laporan penghimpunan dan penyaluran diperbarui berkala.</p>
        </div>
    </div>
</footer>
