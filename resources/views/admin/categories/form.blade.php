@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" data-dirty-form class="space-y-6">
        @csrf
        @if ($category->exists)
            @method('PUT')
        @endif

        <section class="rounded-xl border border-line bg-surface">
            <div class="grid gap-5 px-5 py-5 lg:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Nama Kategori</span>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand" required>
                    @error('name') <span class="mt-2 block text-xs text-[#b42318]">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Slug</span>
                    <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                    @error('slug') <span class="mt-2 block text-xs text-[#b42318]">{{ $message }}</span> @enderror
                </label>

                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Deskripsi</span>
                    <textarea name="description" rows="5" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">{{ old('description', $category->description) }}</textarea>
                    @error('description') <span class="mt-2 block text-xs text-[#b42318]">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Urutan</span>
                    <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                </label>

                <label class="flex items-center gap-3 rounded-lg border border-line px-4 py-3 text-sm font-medium text-ink-soft">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active)) class="h-4 w-4 rounded border-[#b8c8d8] text-brand focus:ring-brand">
                    Kategori aktif
                </label>
            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-line px-5 py-4">
                <a href="{{ route('admin.categories') }}" class="rounded-full border border-line px-5 py-3 text-sm font-medium text-ink-soft">Batal</a>
                <button type="submit" class="rounded-full bg-brand px-5 py-3 text-sm font-semibold text-white">Simpan</button>
            </div>
        </section>
    </form>
@endsection
