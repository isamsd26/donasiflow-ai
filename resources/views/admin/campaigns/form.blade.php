@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ $campaign->exists ? route('admin.campaigns.update', $campaign) : route('admin.campaigns.store') }}" enctype="multipart/form-data" data-dirty-form class="space-y-6">
        @csrf
        @if ($campaign->exists)
            @method('PUT')
        @endif

        <section class="rounded-xl border border-line bg-surface">
            <div class="grid gap-5 px-5 py-5 lg:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Kategori</span>
                    <select name="campaign_category_id" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand" required>
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('campaign_category_id', $campaign->campaign_category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('campaign_category_id') <span class="mt-2 block text-xs text-[#b42318]">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Judul</span>
                    <input type="text" name="title" value="{{ old('title', $campaign->title) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand" required>
                    <span class="mt-2 block text-xs text-muted">Link campaign akan dibuat otomatis dari judul ini.</span>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Nama Lembaga / Pengelola</span>
                    <input type="text" name="organizer_name" value="{{ old('organizer_name', $campaign->organizer_name) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand" required>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Judul Besar di Kartu</span>
                    <input type="text" name="featured_badge" value="{{ old('featured_badge', $campaign->featured_badge) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                    <span class="mt-2 block text-xs text-muted">Opsional. Kalau diisi, teks ini dipakai sebagai judul pendek besar di kartu campaign.</span>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Foto Campaign</span>
                    <input type="file" name="featured_image" accept="image/*" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none file:mr-4 file:rounded-full file:border-0 file:bg-brand file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white">
                    <span class="mt-2 block text-xs text-muted">Upload gambar untuk kartu campaign dan halaman detail.</span>
                    @if ($campaign->featured_image)
                        <span class="mt-2 block text-xs text-muted">Gambar saat ini: {{ $campaign->featured_image }}</span>
                    @endif
                    @error('featured_image') <span class="mt-2 block text-xs text-[#b42318]">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Teks Tombol</span>
                    <input type="text" name="cta_label" value="{{ old('cta_label', $campaign->cta_label) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Urutan Tampil</span>
                    <input type="number" min="0" name="display_order" value="{{ old('display_order', $campaign->display_order ?? 0) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                    <span class="mt-2 block text-xs text-muted">Semakin kecil angkanya, campaign tampil lebih atas.</span>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Target Dana</span>
                    <input type="text" inputmode="numeric" name="target_amount" value="{{ old('target_amount', $campaign->target_amount) }}" data-money-input class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                    <span class="mt-2 block text-xs text-muted">Nominal akan otomatis diformat saat Anda mengetik.</span>
                    @error('target_amount') <span class="mt-2 block text-xs text-[#b42318]">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Lokasi Program</span>
                    <input type="text" name="location" value="{{ old('location', $campaign->location) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Fokus Program</span>
                    <input type="text" name="focus_area" value="{{ old('focus_area', $campaign->focus_area) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Skema Penyaluran</span>
                    <input type="text" name="distribution_plan" value="{{ old('distribution_plan', $campaign->distribution_plan) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Status Penulisan</span>
                    <select name="publish_status" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                        @foreach (['draft', 'published', 'archived'] as $status)
                            <option value="{{ $status }}" @selected(old('publish_status', $campaign->publish_status) === $status)>{{ str($status)->headline() }}</option>
                        @endforeach
                    </select>
                    <span class="mt-2 block text-xs text-muted">`Draft` belum tampil ke publik. `Published` tampil di website. `Archived` disimpan sebagai arsip.</span>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Status Program</span>
                    <select name="public_status" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                        @foreach (['active', 'completed', 'hidden'] as $status)
                            <option value="{{ $status }}" @selected(old('public_status', $campaign->public_status) === $status)>{{ str($status)->headline() }}</option>
                        @endforeach
                    </select>
                    <span class="mt-2 block text-xs text-muted">Gunakan `Active` untuk program berjalan, `Completed` untuk program selesai, dan `Hidden` bila ingin disembunyikan.</span>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Tanggal Mulai</span>
                    <input type="date" name="start_date" value="{{ old('start_date', optional($campaign->start_date)->format('Y-m-d')) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Tanggal Selesai</span>
                    <input type="date" name="end_date" value="{{ old('end_date', optional($campaign->end_date)->format('Y-m-d')) }}" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">
                </label>

                <label class="flex items-center gap-3 rounded-lg border border-line px-4 py-3 text-sm font-medium text-ink-soft lg:col-span-2">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $campaign->is_featured)) class="h-4 w-4 rounded border-[#b8c8d8] text-brand focus:ring-brand">
                    Tampilkan sebagai campaign unggulan
                </label>

                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Ringkasan</span>
                    <textarea name="summary" rows="4" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">{{ old('summary', $campaign->summary) }}</textarea>
                </label>

                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-ink-soft">Konten Lengkap</span>
                    <textarea name="content" rows="10" class="w-full rounded-lg border border-line px-4 py-3 text-sm outline-none focus:border-brand">{{ old('content', $campaign->content) }}</textarea>
                </label>

            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-line px-5 py-4">
                <a href="{{ route('admin.campaigns') }}" class="rounded-full border border-line px-5 py-3 text-sm font-medium text-ink-soft">Batal</a>
                <button type="submit" class="rounded-full bg-brand px-5 py-3 text-sm font-semibold text-white">Simpan Campaign</button>
            </div>
        </section>
    </form>
@endsection
