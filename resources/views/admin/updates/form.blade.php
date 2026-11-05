@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ $update->exists ? route('admin.updates.update', $update) : route('admin.updates.store') }}" data-dirty-form class="space-y-6">
        @csrf
        @if ($update->exists)
            @method('PUT')
        @endif

        <section class="rounded-[26px] bg-white shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
            <div class="grid gap-5 px-5 py-5 lg:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Campaign</span>
                    <select name="campaign_id" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue" required>
                        <option value="">Pilih campaign</option>
                        @foreach ($campaigns as $campaign)
                            <option value="{{ $campaign->id }}" @selected(old('campaign_id', $update->campaign_id) == $campaign->id)>{{ $campaign->title }}</option>
                        @endforeach
                    </select>
                    @error('campaign_id') <span class="mt-2 block text-xs text-[#b42318]">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Judul Update</span>
                    <input type="text" name="title" value="{{ old('title', $update->title) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue" required>
                </label>

                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Excerpt</span>
                    <textarea name="excerpt" rows="3" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">{{ old('excerpt', $update->excerpt) }}</textarea>
                </label>

                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Konten</span>
                    <textarea name="content" rows="10" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">{{ old('content', $update->content) }}</textarea>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Featured Image URL</span>
                    <input type="text" name="featured_image" value="{{ old('featured_image', $update->featured_image) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Tanggal Publikasi</span>
                    <input type="date" name="published_at" value="{{ old('published_at', optional($update->published_at)->format('Y-m-d')) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="flex items-center gap-3 rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm font-medium text-[#334155] lg:col-span-2">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $update->is_published)) class="h-4 w-4 rounded border-[#b8c8d8] text-pm-blue focus:ring-pm-blue">
                    Tampilkan update ini ke publik
                </label>
            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-[#e2e8f0] px-5 py-4">
                <a href="{{ route('admin.updates') }}" class="rounded-full border border-[#e2e8f0] px-5 py-3 text-sm font-medium text-[#334155]">Batal</a>
                <button type="submit" class="rounded-full bg-pm-blue px-5 py-3 text-sm font-semibold text-white">Simpan Update</button>
            </div>
        </section>
    </form>
@endsection
