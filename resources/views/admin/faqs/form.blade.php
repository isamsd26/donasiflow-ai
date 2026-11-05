@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" data-dirty-form class="space-y-6">
        @csrf
        @if ($faq->exists)
            @method('PUT')
        @endif

        <section class="rounded-[26px] bg-white shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
            <div class="grid gap-5 px-5 py-5 lg:grid-cols-2">
                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Pertanyaan</span>
                    <input type="text" name="question" value="{{ old('question', $faq->question) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue" required>
                </label>

                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Jawaban</span>
                    <textarea name="answer" rows="8" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue" required>{{ old('answer', $faq->answer) }}</textarea>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Urutan</span>
                    <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $faq->sort_order) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="flex items-center gap-3 rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm font-medium text-[#334155]">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $faq->is_active)) class="h-4 w-4 rounded border-[#b8c8d8] text-pm-blue focus:ring-pm-blue">
                    Tampilkan FAQ ke publik
                </label>
            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-[#e2e8f0] px-5 py-4">
                <a href="{{ route('admin.faqs') }}" class="rounded-full border border-[#e2e8f0] px-5 py-3 text-sm font-medium text-[#334155]">Batal</a>
                <button type="submit" class="rounded-full bg-pm-blue px-5 py-3 text-sm font-semibold text-white">Simpan FAQ</button>
            </div>
        </section>
    </form>
@endsection
