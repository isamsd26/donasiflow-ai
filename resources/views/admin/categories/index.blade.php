@extends('layouts.admin')

@section('content')
    <section class="rounded-xl border border-line bg-surface">
        <div class="flex flex-col gap-4 border-b border-line px-5 py-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-brand">Modul Administrasi</p>
                <h2 class="mt-1 font-display text-3xl leading-none text-ink">{{ $page['title'] }}</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-muted">{{ $page['description'] }}</p>
            </div>

            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center rounded-full bg-brand px-5 py-3 text-sm font-semibold text-white">
                Tambah Kategori
            </a>
        </div>

        <div class="overflow-x-auto px-5 py-5">
            <table class="min-w-full text-left text-sm">
                <thead class="text-muted">
                    <tr class="border-b border-line">
                        <th class="pb-3 font-semibold">Nama</th>
                        <th class="pb-3 font-semibold">Slug</th>
                        <th class="pb-3 font-semibold">Status</th>
                        <th class="pb-3 font-semibold">Urutan</th>
                        <th class="pb-3 font-semibold">Jumlah Campaign</th>
                        <th class="pb-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr class="border-b border-line last:border-b-0">
                            <td class="py-3 font-medium text-ink">{{ $category->name }}</td>
                            <td class="py-3 text-muted">{{ $category->slug }}</td>
                            <td class="py-3">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $category->is_active ? 'bg-[#dff4e8] text-[#18794e]' : 'bg-[#fff2f2] text-[#b42318]' }}">
                                    {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3 text-ink">{{ $category->sort_order }}</td>
                            <td class="py-3 text-ink">{{ $category->campaigns_count }}</td>
                            <td class="py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="rounded-full border border-line px-3 py-1.5 text-xs font-medium text-ink-soft">Edit</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full bg-[#fff2f2] px-3 py-1.5 text-xs font-medium text-[#b42318]">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-sm text-muted">Belum ada kategori campaign.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-line px-5 py-4">
            {{ $categories->links() }}
        </div>
    </section>
@endsection
