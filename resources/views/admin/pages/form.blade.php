@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ $staticPage->exists ? route('admin.pages.update', $staticPage) : route('admin.pages.store') }}" data-dirty-form class="space-y-6">
        @csrf
        @if ($staticPage->exists)
            @method('PUT')
        @endif

        <section class="rounded-[26px] bg-white shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
            <div class="grid gap-5 px-5 py-5 lg:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Judul</span>
                    <input type="text" name="title" value="{{ old('title', $staticPage->title) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue" required>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Slug</span>
                    <input type="text" name="slug" value="{{ old('slug', $staticPage->slug) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Intro</span>
                    <textarea name="intro" rows="3" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">{{ old('intro', $staticPage->intro) }}</textarea>
                </label>

                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Konten</span>
                    <textarea name="content" rows="12" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">{{ old('content', $staticPage->content) }}</textarea>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">SEO Title</span>
                    <input type="text" name="seo_title" value="{{ old('seo_title', $staticPage->seo_title) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Tanggal Publish</span>
                    <input type="date" name="published_at" value="{{ old('published_at', optional($staticPage->published_at)->format('Y-m-d')) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">SEO Description</span>
                    <textarea name="seo_description" rows="3" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">{{ old('seo_description', $staticPage->seo_description) }}</textarea>
                </label>

                <label class="flex items-center gap-3 rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm font-medium text-[#334155] lg:col-span-2">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $staticPage->is_published)) class="h-4 w-4 rounded border-[#b8c8d8] text-pm-blue focus:ring-pm-blue">
                    Tampilkan halaman ke publik
                </label>
            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-[#e2e8f0] px-5 py-4">
                <a href="{{ route('admin.pages') }}" class="rounded-full border border-[#e2e8f0] px-5 py-3 text-sm font-medium text-[#334155]">Batal</a>
                <button type="submit" class="rounded-full bg-pm-blue px-5 py-3 text-sm font-semibold text-white">Simpan Halaman</button>
            </div>
        </section>
    </form>
@endsection
