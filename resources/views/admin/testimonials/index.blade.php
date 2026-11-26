@extends('layouts.admin')

@section('content')
    <section class="rounded-xl border border-line bg-surface">
        <div class="flex flex-col gap-4 border-b border-line px-5 py-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-brand">Modul Konten</p>
                <h2 class="mt-1 font-display text-3xl leading-none text-ink">{{ $page['title'] }}</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-muted">{{ $page['description'] }}</p>
            </div>

            <a href="{{ route('admin.testimonials.create') }}" class="inline-flex items-center rounded-full bg-brand px-5 py-3 text-sm font-semibold text-white">
                Tambah Testimoni
            </a>
        </div>

        <div class="overflow-x-auto px-5 py-5">
            <table class="min-w-full text-left text-sm">
                <thead class="text-muted">
                    <tr class="border-b border-line">
                        <th class="pb-3 font-semibold">Nama</th>
                        <th class="pb-3 font-semibold">Rating</th>
                        <th class="pb-3 font-semibold">Urutan</th>
                        <th class="pb-3 font-semibold">Aktif</th>
                        <th class="pb-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($testimonials as $testimonial)
                        <tr class="border-b border-line last:border-b-0">
                            <td class="py-3">
                                <p class="font-medium text-ink">{{ $testimonial->name }}</p>
                                <p class="mt-1 text-xs text-muted">
                                    {{ collect([$testimonial->role, $testimonial->location])->filter()->implode(' - ') ?: 'Tanpa detail tambahan' }}
                                </p>
                            </td>
                            <td class="py-3 text-ink">{{ $testimonial->rating }}/5</td>
                            <td class="py-3 text-ink">{{ $testimonial->sort_order }}</td>
                            <td class="py-3">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $testimonial->is_active ? 'bg-[#dff4e8] text-[#18794e]' : 'bg-[#fff2f2] text-[#b42318]' }}">
                                    {{ $testimonial->is_active ? 'Ya' : 'Tidak' }}
                                </span>
                            </td>
                            <td class="py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="rounded-full border border-line px-3 py-1.5 text-xs font-medium text-ink-soft">Edit</a>
                                    <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" onsubmit="return confirm('Hapus testimoni ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full bg-[#fff2f2] px-3 py-1.5 text-xs font-medium text-[#b42318]">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-sm text-muted">Belum ada testimoni publik.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-line px-5 py-4">
            {{ $testimonials->links() }}
        </div>
    </section>
@endsection
