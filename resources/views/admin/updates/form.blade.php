@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ $update->exists ? route('admin.updates.update', $update) : route('admin.updates.store') }}" data-dirty-form class="space-y-6">
        @csrf
        @if ($update->exists)
            @method('PUT')
        @endif

        <section class="rounded-xl border border-line bg-surface">
            <div class="grid gap-5 px-5 py-5 lg:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Campaign</span>
                    <select name="campaign_id" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand" required>
                        <option value="">Pilih campaign</option>
                        @foreach ($campaigns as $campaign)
                            <option value="{{ $campaign->id }}" @selected(old('campaign_id', $update->campaign_id) == $campaign->id)>{{ $campaign->title }}</option>
                        @endforeach
                    </select>
                    @error('campaign_id') <span class="mt-2 block text-xs text-[#b42318]">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Judul Update</span>
                    <input type="text" name="title" value="{{ old('title', $update->title) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand" required>
                </label>

                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Excerpt</span>
                    <textarea name="excerpt" rows="3" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">{{ old('excerpt', $update->excerpt) }}</textarea>
                </label>

                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Konten</span>
                    <textarea name="content" rows="10" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">{{ old('content', $update->content) }}</textarea>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Featured Image URL</span>
                    <input type="text" name="featured_image" value="{{ old('featured_image', $update->featured_image) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Tanggal Publikasi</span>
                    <input type="date" name="published_at" value="{{ old('published_at', optional($update->published_at)->format('Y-m-d')) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                </label>

                <label class="flex items-center gap-3 rounded-lg border border-line px-4 py-3 text-sm font-medium text-ink-soft lg:col-span-2">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $update->is_published)) class="h-4 w-4 rounded border-[#b8c8d8] text-brand focus:ring-brand">
                    Tampilkan update ini ke publik
                </label>
            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-line px-5 py-4">
                <a href="{{ route('admin.updates') }}" class="rounded-full border border-line px-5 py-3 text-sm font-medium text-ink-soft">Batal</a>
                <button type="submit" class="rounded-full bg-brand px-5 py-3 text-sm font-semibold text-white">Simpan Update</button>
            </div>
        </section>
    </form>
@endsection
