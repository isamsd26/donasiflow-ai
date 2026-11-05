@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ $setting->exists ? route('admin.settings.update', $setting) : route('admin.settings.store') }}" data-dirty-form class="space-y-6">
        @csrf
        @if ($setting->exists)
            @method('PUT')
        @endif

        <section class="rounded-[26px] bg-white shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
            <div class="grid gap-5 px-5 py-5 lg:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Group</span>
                    <input type="text" name="group" value="{{ old('group', $setting->group) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Key</span>
                    <input type="text" name="key" value="{{ old('key', $setting->key) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue" required>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Label</span>
                    <input type="text" name="label" value="{{ old('label', $setting->label) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue" required>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Type</span>
                    <select name="type" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                        @foreach (['text', 'textarea', 'url', 'email', 'json'] as $type)
                            <option value="{{ $type }}" @selected(old('type', $setting->type) === $type)>{{ strtoupper($type) }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Value</span>
                    <textarea name="value_text" rows="8" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 font-mono text-sm outline-none focus:border-pm-blue">{{ old('value_text', $valueText) }}</textarea>
                    @error('value_text') <span class="mt-2 block text-xs text-[#b42318]">{{ $message }}</span> @enderror
                </label>
            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-[#e2e8f0] px-5 py-4">
                <a href="{{ route('admin.settings') }}" class="rounded-full border border-[#e2e8f0] px-5 py-3 text-sm font-medium text-[#334155]">Batal</a>
                <button type="submit" class="rounded-full bg-pm-blue px-5 py-3 text-sm font-semibold text-white">Simpan Setting</button>
            </div>
        </section>
    </form>
@endsection
