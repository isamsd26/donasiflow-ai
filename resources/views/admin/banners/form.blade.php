@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" enctype="multipart/form-data" data-dirty-form class="space-y-6">
        @csrf
        @if ($banner->exists)
            @method('PUT')
        @endif

        <section class="rounded-xl border border-line bg-surface">
            <div class="grid gap-5 px-5 py-5 lg:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Campaign Terkait</span>
                    <select name="campaign_id" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                        <option value="">Tanpa campaign</option>
                        @foreach ($campaigns as $campaign)
                            <option value="{{ $campaign->id }}" @selected(old('campaign_id', $banner->campaign_id) == $campaign->id)>{{ $campaign->title }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Judul</span>
                    <input type="text" name="title" value="{{ old('title', $banner->title) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand" required>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Eyebrow</span>
                    <input type="text" name="eyebrow" value="{{ old('eyebrow', $banner->eyebrow) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Urutan</span>
                    <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                </label>

                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Subtitle</span>
                    <textarea name="subtitle" rows="4" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">{{ old('subtitle', $banner->subtitle) }}</textarea>
                </label>

                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Gambar Hero</span>
                    <input type="file" name="image_path" accept="image/*" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none file:mr-4 file:rounded-full file:border-0 file:bg-brand file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white">
                    @if ($banner->image_path)
                        <span class="mt-2 block text-xs text-muted">Gambar saat ini: {{ $banner->image_path }}</span>
                    @endif
                    @error('image_path') <span class="mt-2 block text-xs text-[#b42318]">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Teks Tombol</span>
                    <input type="text" name="cta_label" value="{{ old('cta_label', $banner->cta_label) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Link Tombol</span>
                    <input type="text" name="cta_url" value="{{ old('cta_url', $banner->cta_url) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                </label>

                <label class="flex items-center gap-3 rounded-lg border border-line px-4 py-3 text-sm font-medium text-ink-soft lg:col-span-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner->is_active)) class="h-4 w-4 rounded border-[#b8c8d8] text-brand focus:ring-brand">
                    Banner aktif
                </label>
            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-line px-5 py-4">
                <a href="{{ route('admin.banners') }}" class="rounded-full border border-line px-5 py-3 text-sm font-medium text-ink-soft">Batal</a>
                <button type="submit" class="rounded-full bg-brand px-5 py-3 text-sm font-semibold text-white">Simpan Banner</button>
            </div>
        </section>
    </form>
@endsection
