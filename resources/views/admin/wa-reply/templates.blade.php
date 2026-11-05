@extends('layouts.admin')

@section('content')
    <section class="rounded-[26px] bg-white shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[#e2e8f0] px-5 py-5">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Modul Komunikasi</p>
                <h2 class="mt-1 font-display text-3xl leading-none text-[#1e293b]">{{ $page['title'] }}</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-[#64748b]">{{ $page['description'] }}</p>
            </div>
            <div>
                <a href="{{ route('admin.wa-reply.index') }}" class="rounded-full bg-[#f8fafc] border border-[#e2e8f0] px-4 py-2 text-sm font-semibold text-pm-blue hover:bg-[#e2e8f0]">Ke Halaman Kirim WA</a>
            </div>
        </div>

        <div class="grid gap-6 p-6 lg:grid-cols-[300px_1fr]">
            <!-- Form Tambah Template -->
            <div class="rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] p-5 h-fit">
                <h3 class="font-display text-xl text-[#1e293b] mb-4">Tambah Template Baru</h3>
                <form action="{{ route('admin.wa-templates.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-[#64748b] mb-1">Judul Template</label>
                        <input type="text" name="title" required class="w-full rounded-xl border border-[#e2e8f0] px-3 py-2 text-sm outline-none focus:border-pm-blue" placeholder="Misal: Terima Kasih Lunas">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#64748b] mb-1">Isi Pesan WA</label>
                        <textarea name="message" required rows="5" class="w-full rounded-xl border border-[#e2e8f0] px-3 py-2 text-sm outline-none focus:border-pm-blue" placeholder="Tuliskan pesan balasan..."></textarea>
                        <p class="mt-1 text-[10px] text-pm-muted">Anda bisa menyalin variabel donatur manual nanti saat membalas, atau gunakan: {nama}, {campaign}, {jumlah}</p>
                    </div>
                    <button type="submit" class="w-full rounded-full bg-pm-blue px-4 py-2 text-sm font-semibold text-white">Simpan Template</button>
                </form>
            </div>

            <!-- Daftar Template -->
            <div class="space-y-4">
                @forelse($templates as $template)
                    <div class="rounded-2xl border border-[#e2e8f0] p-5 flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-[#1e293b]">{{ $template->title }}</h4>
                            
                            <form action="{{ route('admin.wa-templates.destroy', $template) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus template ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                            </form>
                        </div>
                        <div class="rounded-xl bg-[#f8fafc] p-3 text-sm text-[#334155] whitespace-pre-wrap">{{ $template->message }}</div>
                        
                        <details class="group">
                            <summary class="cursor-pointer text-xs font-semibold text-pm-blue">Edit Template</summary>
                            <form action="{{ route('admin.wa-templates.update', $template) }}" method="POST" class="mt-3 space-y-3 p-3 border border-[#e2e8f0] rounded-xl bg-white">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <label class="block text-xs font-semibold text-[#64748b] mb-1">Judul</label>
                                    <input type="text" name="title" value="{{ $template->title }}" required class="w-full rounded-lg border border-[#e2e8f0] px-3 py-1.5 text-sm outline-none focus:border-pm-blue">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-[#64748b] mb-1">Isi Pesan</label>
                                    <textarea name="message" required rows="3" class="w-full rounded-lg border border-[#e2e8f0] px-3 py-1.5 text-sm outline-none focus:border-pm-blue">{{ $template->message }}</textarea>
                                </div>
                                <button type="submit" class="rounded-full bg-pm-blue px-4 py-1.5 text-xs font-semibold text-white">Simpan Perubahan</button>
                            </form>
                        </details>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-[#e2e8f0] p-8 text-center text-sm text-[#64748b]">
                        Belum ada template balasan yang ditambahkan.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
