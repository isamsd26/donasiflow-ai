@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ $campaign->exists ? route('admin.campaigns.update', $campaign) : route('admin.campaigns.store') }}" enctype="multipart/form-data" data-dirty-form class="space-y-6">
        @csrf
        @if ($campaign->exists)
            @method('PUT')
        @endif

        <section class="rounded-[26px] bg-white shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
            <div class="grid gap-5 px-5 py-5 lg:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Kategori</span>
                    <select name="campaign_category_id" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue" required>
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('campaign_category_id', $campaign->campaign_category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('campaign_category_id') <span class="mt-2 block text-xs text-[#b42318]">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Judul</span>
                    <input type="text" name="title" value="{{ old('title', $campaign->title) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue" required>
                    <span class="mt-2 block text-xs text-[#64748b]">Link campaign akan dibuat otomatis dari judul ini.</span>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Nama Lembaga / Pengelola</span>
                    <input type="text" name="organizer_name" value="{{ old('organizer_name', $campaign->organizer_name) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue" required>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Judul Besar di Kartu</span>
                    <input type="text" name="featured_badge" value="{{ old('featured_badge', $campaign->featured_badge) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                    <span class="mt-2 block text-xs text-[#64748b]">Opsional. Kalau diisi, teks ini dipakai sebagai judul pendek besar di kartu campaign.</span>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Foto Campaign</span>
                    <input type="file" name="featured_image" accept="image/*" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none file:mr-4 file:rounded-full file:border-0 file:bg-pm-blue file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white">
                    <span class="mt-2 block text-xs text-[#64748b]">Upload gambar untuk kartu campaign dan halaman detail.</span>
                    @if ($campaign->featured_image)
                        <span class="mt-2 block text-xs text-[#64748b]">Gambar saat ini: {{ $campaign->featured_image }}</span>
                    @endif
                    @error('featured_image') <span class="mt-2 block text-xs text-[#b42318]">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Teks Tombol</span>
                    <input type="text" name="cta_label" value="{{ old('cta_label', $campaign->cta_label) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Urutan Tampil</span>
                    <input type="number" min="0" name="display_order" value="{{ old('display_order', $campaign->display_order ?? 0) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                    <span class="mt-2 block text-xs text-[#64748b]">Semakin kecil angkanya, campaign tampil lebih atas.</span>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Target Dana</span>
                    <input type="text" inputmode="numeric" name="target_amount" value="{{ old('target_amount', $campaign->target_amount) }}" data-money-input class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                    <span class="mt-2 block text-xs text-[#64748b]">Nominal akan otomatis diformat saat Anda mengetik.</span>
                    @error('target_amount') <span class="mt-2 block text-xs text-[#b42318]">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Lokasi Program</span>
                    <input type="text" name="location" value="{{ old('location', $campaign->location) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Fokus Program</span>
                    <input type="text" name="focus_area" value="{{ old('focus_area', $campaign->focus_area) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Skema Penyaluran</span>
                    <input type="text" name="distribution_plan" value="{{ old('distribution_plan', $campaign->distribution_plan) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Status Penulisan</span>
                    <select name="publish_status" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                        @foreach (['draft', 'published', 'archived'] as $status)
                            <option value="{{ $status }}" @selected(old('publish_status', $campaign->publish_status) === $status)>{{ str($status)->headline() }}</option>
                        @endforeach
                    </select>
                    <span class="mt-2 block text-xs text-[#64748b]">`Draft` belum tampil ke publik. `Published` tampil di website. `Archived` disimpan sebagai arsip.</span>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Status Program</span>
                    <select name="public_status" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                        @foreach (['active', 'completed', 'hidden'] as $status)
                            <option value="{{ $status }}" @selected(old('public_status', $campaign->public_status) === $status)>{{ str($status)->headline() }}</option>
                        @endforeach
                    </select>
                    <span class="mt-2 block text-xs text-[#64748b]">Gunakan `Active` untuk program berjalan, `Completed` untuk program selesai, dan `Hidden` bila ingin disembunyikan.</span>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Tanggal Mulai</span>
                    <input type="date" name="start_date" value="{{ old('start_date', optional($campaign->start_date)->format('Y-m-d')) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Tanggal Selesai</span>
                    <input type="date" name="end_date" value="{{ old('end_date', optional($campaign->end_date)->format('Y-m-d')) }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>

                <label class="flex items-center gap-3 rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm font-medium text-[#334155] lg:col-span-2">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $campaign->is_featured)) class="h-4 w-4 rounded border-[#b8c8d8] text-pm-blue focus:ring-pm-blue">
                    Tampilkan sebagai campaign unggulan
                </label>

                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Ringkasan</span>
                    <textarea name="summary" rows="4" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">{{ old('summary', $campaign->summary) }}</textarea>
                </label>

                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-[#334155]">Konten Lengkap</span>
                    <textarea name="content" rows="10" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">{{ old('content', $campaign->content) }}</textarea>
                </label>

            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-[#e2e8f0] px-5 py-4">
                <a href="{{ route('admin.campaigns') }}" class="rounded-full border border-[#e2e8f0] px-5 py-3 text-sm font-medium text-[#334155]">Batal</a>
                <button type="submit" class="rounded-full bg-pm-blue px-5 py-3 text-sm font-semibold text-white">Simpan Campaign</button>
            </div>
        </section>
    </form>
@endsection
