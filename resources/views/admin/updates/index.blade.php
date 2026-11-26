@extends('layouts.admin')

@section('content')
    <section class="rounded-xl border border-line bg-surface">
        <div class="flex flex-col gap-4 border-b border-line px-5 py-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-brand">Modul Administrasi</p>
                <h2 class="mt-1 font-display text-3xl leading-none text-ink">{{ $page['title'] }}</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-muted">{{ $page['description'] }}</p>
            </div>

            <a href="{{ route('admin.updates.create') }}" class="inline-flex items-center rounded-full bg-brand px-5 py-3 text-sm font-semibold text-white">
                Tambah Update
            </a>
        </div>

        <div class="overflow-x-auto px-5 py-5">
            <table class="min-w-full text-left text-sm">
                <thead class="text-muted">
                    <tr class="border-b border-line">
                        <th class="pb-3 font-semibold">Judul</th>
                        <th class="pb-3 font-semibold">Campaign</th>
                        <th class="pb-3 font-semibold">Tanggal Publikasi</th>
                        <th class="pb-3 font-semibold">Status</th>
                        <th class="pb-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($updates as $update)
                        <tr class="border-b border-line last:border-b-0">
                            <td class="py-3">
                                <p class="font-medium text-ink">{{ $update->title }}</p>
                                <p class="mt-1 text-xs text-muted">{{ \Illuminate\Support\Str::limit($update->excerpt, 70) }}</p>
                            </td>
                            <td class="py-3 text-ink">{{ $update->campaign?->title ?? '-' }}</td>
                            <td class="py-3 text-ink">{{ optional($update->published_at)->format('d M Y') ?? '-' }}</td>
                            <td class="py-3">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $update->is_published ? 'bg-[#dff4e8] text-[#18794e]' : 'bg-[#fff4da] text-[#9a6700]' }}">
                                    {{ $update->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td class="py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.updates.edit', $update) }}" class="rounded-full border border-line px-3 py-1.5 text-xs font-medium text-ink-soft">Edit</a>
                                    <form method="POST" action="{{ route('admin.updates.destroy', $update) }}" onsubmit="return confirm('Hapus update ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full bg-[#fff2f2] px-3 py-1.5 text-xs font-medium text-[#b42318]">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-sm text-muted">Belum ada update campaign.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-line px-5 py-4">
            {{ $updates->links() }}
        </div>
    </section>
@endsection
