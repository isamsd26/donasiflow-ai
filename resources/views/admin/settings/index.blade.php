@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ route('admin.settings.save') }}" data-dirty-form class="space-y-6">
        @csrf
        @method('PUT')

        <section class="rounded-[26px] bg-white shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
            <div class="border-b border-[#e2e8f0] px-5 py-5">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Pengaturan Website</p>
                <h2 class="mt-1 font-display text-3xl leading-none text-[#1e293b]">{{ $page['title'] }}</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-[#64748b]">{{ $page['description'] }}</p>
            </div>

            <div class="space-y-6 px-5 py-5">
                @foreach ($sections as $section)
                    <section class="rounded-[24px] border border-[#e2e8f0] bg-[#ffffff]">
                        <div class="border-b border-[#e8eef6] px-5 py-4">
                            <h3 class="text-lg font-semibold text-[#1e293b]">{{ $section['title'] }}</h3>
                            <p class="mt-1 text-sm leading-6 text-[#64748b]">{{ $section['description'] }}</p>
                        </div>

                        <div class="grid gap-5 px-5 py-5 lg:grid-cols-2">
                            @foreach ($section['fields'] as $field)
                                <label class="block {{ ! empty($field['full']) ? 'lg:col-span-2' : '' }}">
                                    <span class="mb-2 block text-sm font-medium text-[#334155]">{{ $field['label'] }}</span>

                                    @if ($field['type'] === 'textarea')
                                        <textarea
                                            name="{{ $field['key'] }}"
                                            rows="{{ str_contains($field['key'], 'menu') ? 6 : 4 }}"
                                            class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue"
                                        >{{ old($field['key'], $values[$field['key']] ?? '') }}</textarea>
                                    @else
                                        <input
                                            type="{{ $field['type'] }}"
                                            name="{{ $field['key'] }}"
                                            value="{{ old($field['key'], $values[$field['key']] ?? '') }}"
                                            class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue"
                                        >
                                    @endif

                                    @if (! empty($field['help']))
                                        <span class="mt-2 block text-xs leading-5 text-[#64748b]">{{ $field['help'] }}</span>
                                    @endif

                                    @if ($field['key'] === 'footer_navigation_menu_text')
                                        <span class="mt-2 block rounded-2xl bg-[#eef4fb] px-4 py-3 text-xs leading-6 text-[#44617f]">Contoh:
                                            <br>{!! str_replace("\n", '<br>', e($menuExamples['navigation'])) !!}
                                        </span>
                                    @endif

                                    @if ($field['key'] === 'footer_support_menu_text')
                                        <span class="mt-2 block rounded-2xl bg-[#eef4fb] px-4 py-3 text-xs leading-6 text-[#44617f]">Contoh:
                                            <br>{!! str_replace("\n", '<br>', e($menuExamples['support'])) !!}
                                        </span>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-[#e2e8f0] px-5 py-4">
                <button type="submit" class="rounded-full bg-pm-blue px-5 py-3 text-sm font-semibold text-white">Simpan Pengaturan</button>
            </div>
        </section>
    </form>
@endsection
