@extends('layouts.admin')

@section('content')
    <section class="rounded-xl border border-line bg-surface">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-line px-5 py-5">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-brand">Modul Komunikasi</p>
                <h2 class="mt-1 font-display text-3xl leading-none text-ink">{{ $page['title'] }}</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-muted">{{ $page['description'] }}</p>
            </div>
            <div>
                <a href="{{ route('admin.wa-reply.index') }}" class="rounded-full bg-canvas border border-line px-4 py-2 text-sm font-semibold text-brand hover:bg-[#e2e8f0]">Ke Halaman Kirim WA</a>
            </div>
        </div>

        <div class="grid gap-6 p-6 lg:grid-cols-[300px_1fr]">
            <!-- Form Tambah Template -->
            <div class="rounded-lg border border-line bg-canvas p-5 h-fit">
                <h3 class="font-display text-xl text-ink mb-4">Tambah Template Baru</h3>
                <form action="{{ route('admin.wa-templates.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-muted mb-1">Judul Template</label>
                        <input type="text" name="title" required class="w-full rounded-xl border border-line px-3 py-2 text-sm outline-none focus:border-brand" placeholder="Misal: Terima Kasih Lunas">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-muted mb-1">Isi Pesan WA</label>
                        <textarea name="message" required rows="5" class="w-full rounded-xl border border-line px-3 py-2 text-sm outline-none focus:border-brand" placeholder="Tuliskan pesan balasan..."></textarea>
                        <p class="mt-1 text-[10px] text-muted">Anda bisa menyalin variabel donatur manual nanti saat membalas, atau gunakan: {nama}, {campaign}, {jumlah}</p>
                    </div>
                    <button type="submit" class="w-full rounded-full bg-brand px-4 py-2 text-sm font-semibold text-white">Simpan Template</button>
                </form>
            </div>

            <!-- Daftar Template -->
            <div class="space-y-4">
                @forelse($templates as $template)
                    <div class="rounded-lg border border-line p-5 flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-ink">{{ $template->title }}</h4>
                            
                            <form action="{{ route('admin.wa-templates.destroy', $template) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus template ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                            </form>
                        </div>
                        <div class="rounded-xl bg-canvas p-3 text-sm text-ink-soft whitespace-pre-wrap">{{ $template->message }}</div>
                        
                        <details class="group">
                            <summary class="cursor-pointer text-xs font-semibold text-brand">Edit Template</summary>
                            <form action="{{ route('admin.wa-templates.update', $template) }}" method="POST" class="mt-3 space-y-3 p-3 border border-line rounded-xl bg-white">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <label class="block text-xs font-semibold text-muted mb-1">Judul</label>
                                    <input type="text" name="title" value="{{ $template->title }}" required class="w-full rounded-lg border border-line px-3 py-1.5 text-sm outline-none focus:border-brand">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-muted mb-1">Isi Pesan</label>
                                    <textarea name="message" required rows="3" class="w-full rounded-lg border border-line px-3 py-1.5 text-sm outline-none focus:border-brand">{{ $template->message }}</textarea>
                                </div>
                                <button type="submit" class="rounded-full bg-brand px-4 py-1.5 text-xs font-semibold text-white">Simpan Perubahan</button>
                            </form>
                        </details>
                    </div>
                @empty
                    <div class="rounded-lg border border-dashed border-line p-8 text-center text-sm text-muted">
                        Belum ada template balasan yang ditambahkan.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
