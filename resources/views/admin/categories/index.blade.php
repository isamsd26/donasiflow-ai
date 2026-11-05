@extends('layouts.admin')

@section('content')
    <section class="rounded-[26px] bg-white shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
        <div class="flex flex-col gap-4 border-b border-[#e2e8f0] px-5 py-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Modul Administrasi</p>
                <h2 class="mt-1 font-display text-3xl leading-none text-[#1e293b]">{{ $page['title'] }}</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-[#64748b]">{{ $page['description'] }}</p>
            </div>

            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center rounded-full bg-pm-blue px-5 py-3 text-sm font-semibold text-white">
                Tambah Kategori
            </a>
        </div>

        <div class="overflow-x-auto px-5 py-5">
            <table class="min-w-full text-left text-sm">
                <thead class="text-[#64748b]">
                    <tr class="border-b border-[#e9eff6]">
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
                        <tr class="border-b border-[#f1f5f9] last:border-b-0">
                            <td class="py-3 font-medium text-[#1e293b]">{{ $category->name }}</td>
                            <td class="py-3 text-[#64748b]">{{ $category->slug }}</td>
                            <td class="py-3">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $category->is_active ? 'bg-[#dff4e8] text-[#18794e]' : 'bg-[#fff2f2] text-[#b42318]' }}">
                                    {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3 text-[#1e293b]">{{ $category->sort_order }}</td>
                            <td class="py-3 text-[#1e293b]">{{ $category->campaigns_count }}</td>
                            <td class="py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="rounded-full border border-[#e2e8f0] px-3 py-1.5 text-xs font-medium text-[#334155]">Edit</a>
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
                            <td colspan="6" class="py-8 text-center text-sm text-[#64748b]">Belum ada kategori campaign.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-[#e2e8f0] px-5 py-4">
            {{ $categories->links() }}
        </div>
    </section>
@endsection
