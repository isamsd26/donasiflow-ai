@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" enctype="multipart/form-data" data-dirty-form class="space-y-6">
        @csrf
        @if ($banner->exists)
            @method('PUT')
        @endif

        <section class="rounded-[26px] bg-white shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
            <div class="grid gap-5 px-5 py-5 lg:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Campaign Terkait</span>
                    <select name="campaign_id" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                        <option value="">Tanpa campaign</option>
                        @foreach ($campaigns as $campaign)
                            <option value="{{ $campaign->id }}" @selected(old('campaign_id', $banner->campaign_id) == $campaign->id)>{{ $campaign->title }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Judul</span>
                    <input type="text" name="title" value="{{ old('title', $banner->title) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue" required>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Eyebrow</span>
                    <input type="text" name="eyebrow" value="{{ old('eyebrow', $banner->eyebrow) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Urutan</span>
                    <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Subtitle</span>
                    <textarea name="subtitle" rows="4" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">{{ old('subtitle', $banner->subtitle) }}</textarea>
                </label>

                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Gambar Hero</span>
                    <input type="file" name="image_path" accept="image/*" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none file:mr-4 file:rounded-full file:border-0 file:bg-pm-blue file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white">
                    @if ($banner->image_path)
                        <span class="mt-2 block text-xs text-[#64748b]">Gambar saat ini: {{ $banner->image_path }}</span>
                    @endif
                    @error('image_path') <span class="mt-2 block text-xs text-[#b42318]">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Teks Tombol</span>
                    <input type="text" name="cta_label" value="{{ old('cta_label', $banner->cta_label) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Link Tombol</span>
                    <input type="text" name="cta_url" value="{{ old('cta_url', $banner->cta_url) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="flex items-center gap-3 rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm font-medium text-[#334155] lg:col-span-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner->is_active)) class="h-4 w-4 rounded border-[#b8c8d8] text-pm-blue focus:ring-pm-blue">
                    Banner aktif
                </label>
            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-[#e2e8f0] px-5 py-4">
                <a href="{{ route('admin.banners') }}" class="rounded-full border border-[#e2e8f0] px-5 py-3 text-sm font-medium text-[#334155]">Batal</a>
                <button type="submit" class="rounded-full bg-pm-blue px-5 py-3 text-sm font-semibold text-white">Simpan Banner</button>
            </div>
        </section>
    </form>
@endsection
