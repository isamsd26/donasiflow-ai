@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}" data-dirty-form class="space-y-6">
        @csrf
        @if ($testimonial->exists)
            @method('PUT')
        @endif

        <section class="rounded-[26px] bg-white shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
            <div class="grid gap-5 px-5 py-5 lg:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Nama</span>
                    <input type="text" name="name" value="{{ old('name', $testimonial->name) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue" required>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Peran / Jabatan</span>
                    <input type="text" name="role" value="{{ old('role', $testimonial->role) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Lokasi</span>
                    <input type="text" name="location" value="{{ old('location', $testimonial->location) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Rating</span>
                    <input type="number" min="1" max="5" name="rating" value="{{ old('rating', $testimonial->rating) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Kutipan Testimoni</span>
                    <textarea name="quote" rows="7" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue" required>{{ old('quote', $testimonial->quote) }}</textarea>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Urutan</span>
                    <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $testimonial->sort_order) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="flex items-center gap-3 rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm font-medium text-[#334155]">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $testimonial->is_active)) class="h-4 w-4 rounded border-[#b8c8d8] text-pm-blue focus:ring-pm-blue">
                    Tampilkan testimoni ke publik
                </label>
            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-[#e2e8f0] px-5 py-4">
                <a href="{{ route('admin.testimonials') }}" class="rounded-full border border-[#e2e8f0] px-5 py-3 text-sm font-medium text-[#334155]">Batal</a>
                <button type="submit" class="rounded-full bg-pm-blue px-5 py-3 text-sm font-semibold text-white">Simpan Testimoni</button>
            </div>
        </section>
    </form>
@endsection
