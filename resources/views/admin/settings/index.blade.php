@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ route('admin.settings.save') }}" data-dirty-form class="space-y-6">
        @csrf
        @method('PUT')

        <section class="rounded-xl border border-line bg-surface">
            <div class="border-b border-line px-5 py-5">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-brand">Pengaturan Website</p>
                <h2 class="mt-1 font-display text-3xl leading-none text-ink">{{ $page['title'] }}</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-muted">{{ $page['description'] }}</p>
            </div>

            <div class="space-y-6 px-5 py-5">
                @foreach ($sections as $section)
                    <section class="rounded-xl border border-line bg-[#ffffff]">
                        <div class="border-b border-[#e8eef6] px-5 py-4">
                            <h3 class="text-lg font-semibold text-ink">{{ $section['title'] }}</h3>
                            <p class="mt-1 text-sm leading-6 text-muted">{{ $section['description'] }}</p>
                        </div>

                        <div class="grid gap-5 px-5 py-5 lg:grid-cols-2">
                            @foreach ($section['fields'] as $field)
                                <label class="block {{ ! empty($field['full']) ? 'lg:col-span-2' : '' }}">
                                    <span class="mb-2 block text-sm font-medium text-ink-soft">{{ $field['label'] }}</span>

                                    @if ($field['type'] === 'textarea')
                                        <textarea
                                            name="{{ $field['key'] }}"
                                            rows="{{ str_contains($field['key'], 'menu') ? 6 : 4 }}"
                                            class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand"
                                        >{{ old($field['key'], $values[$field['key']] ?? '') }}</textarea>
                                    @else
                                        <input
                                            type="{{ $field['type'] }}"
                                            name="{{ $field['key'] }}"
                                            value="{{ old($field['key'], $values[$field['key']] ?? '') }}"
                                            class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand"
                                        >
                                    @endif

                                    @if (! empty($field['help']))
                                        <span class="mt-2 block text-xs leading-5 text-muted">{{ $field['help'] }}</span>
                                    @endif

                                    @if ($field['key'] === 'footer_navigation_menu_text')
                                        <span class="mt-2 block rounded-lg bg-[#eef4fb] px-4 py-3 text-xs leading-6 text-[#44617f]">Contoh:
                                            <br>{!! str_replace("\n", '<br>', e($menuExamples['navigation'])) !!}
                                        </span>
                                    @endif

                                    @if ($field['key'] === 'footer_support_menu_text')
                                        <span class="mt-2 block rounded-lg bg-[#eef4fb] px-4 py-3 text-xs leading-6 text-[#44617f]">Contoh:
                                            <br>{!! str_replace("\n", '<br>', e($menuExamples['support'])) !!}
                                        </span>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-line px-5 py-4">
                <button type="submit" class="rounded-full bg-brand px-5 py-3 text-sm font-semibold text-white">Simpan Pengaturan</button>
            </div>
        </section>
    </form>
@endsection
