@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ $setting->exists ? route('admin.settings.update', $setting) : route('admin.settings.store') }}" data-dirty-form class="space-y-6">
        @csrf
        @if ($setting->exists)
            @method('PUT')
        @endif

        <section class="rounded-xl border border-line bg-surface">
            <div class="grid gap-5 px-5 py-5 lg:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Group</span>
                    <input type="text" name="group" value="{{ old('group', $setting->group) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Key</span>
                    <input type="text" name="key" value="{{ old('key', $setting->key) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand" required>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Label</span>
                    <input type="text" name="label" value="{{ old('label', $setting->label) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand" required>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Type</span>
                    <select name="type" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                        @foreach (['text', 'textarea', 'url', 'email', 'json'] as $type)
                            <option value="{{ $type }}" @selected(old('type', $setting->type) === $type)>{{ strtoupper($type) }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Value</span>
                    <textarea name="value_text" rows="8" class="w-full rounded-lg border border-line px-4 py-3 font-mono text-sm outline-none focus:border-brand">{{ old('value_text', $valueText) }}</textarea>
                    @error('value_text') <span class="mt-2 block text-xs text-[#b42318]">{{ $message }}</span> @enderror
                </label>
            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-line px-5 py-4">
                <a href="{{ route('admin.settings') }}" class="rounded-full border border-line px-5 py-3 text-sm font-medium text-ink-soft">Batal</a>
                <button type="submit" class="rounded-full bg-brand px-5 py-3 text-sm font-semibold text-white">Simpan Setting</button>
            </div>
        </section>
    </form>
@endsection
