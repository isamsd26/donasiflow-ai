@extends('layouts.admin')

@section('content')
    <section class="rounded-xl border border-line bg-surface flex flex-col h-full min-h-[70vh]">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-line px-5 py-5 shrink-0">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-brand">Modul Komunikasi</p>
                <h2 class="mt-1 font-display text-3xl leading-none text-ink">{{ $page['title'] }}</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-muted">{{ $page['description'] }}</p>
            </div>
            <div>
                <a href="{{ route('admin.wa-templates.index') }}" class="rounded-full border border-line bg-canvas px-4 py-2 text-sm font-semibold text-brand hover:bg-[#e2e8f0]">Kelola Template</a>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row flex-1 lg:overflow-hidden">
            <!-- Panel Kiri: Donasi Paid -->
            <div class="w-full lg:w-1/2 flex flex-col border-b lg:border-b-0 lg:border-r border-line bg-white">
                <div class="p-5 border-b border-line bg-canvas shrink-0">
                    <form method="GET" action="{{ route('admin.wa-reply.index') }}" class="flex gap-2">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, hp, referensi, campaign..." class="flex-1 rounded-full border border-line px-4 py-2 text-sm outline-none focus:border-brand">
                        <button type="submit" class="rounded-full bg-brand px-4 py-2 text-sm font-semibold text-white">Cari</button>
                        @if(request('q'))
                            <a href="{{ route('admin.wa-reply.index') }}" class="rounded-full border border-line bg-white px-4 py-2 text-sm font-semibold text-muted">Reset</a>
                        @endif
                    </form>
                </div>
                
                <div class="flex-1 lg:overflow-y-auto p-5 space-y-3 lg:max-h-[600px]">
                    @forelse($donations as $donation)
                        <div class="rounded-lg border border-line p-4 hover:border-brand hover:shadow-sm transition cursor-pointer flex justify-between items-center donation-item" 
                             data-donation="{{ json_encode([
                                 'nama' => $donation->donor_name,
                                 'campaign' => $donation->campaign?->title ?? '-',
                                 'jumlah' => 'Rp ' . number_format((float) $donation->amount, 0, ',', '.'),
                                 'phone' => preg_replace('/[^\d]/', '', $donation->donor_phone)
                             ]) }}">
                            <div>
                                <p class="text-sm font-semibold text-ink">{{ $donation->donor_name }}</p>
                                <p class="text-xs text-muted mt-1">{{ $donation->donor_phone ?: 'Tidak ada nomor HP' }}</p>
                                <p class="text-xs text-muted mt-1 truncate max-w-[200px]">{{ $donation->campaign?->title ?? '-' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-semibold text-brand">{{ $donation->paid_at ? $donation->paid_at->translatedFormat('d M Y') : '-' }}</p>
                                <button type="button" class="mt-2 rounded-full bg-canvas border border-line px-3 py-1 text-xs font-semibold text-brand select-btn">Pilih</button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-sm text-muted">
                            Belum ada donasi berstatus lunas (Paid).
                        </div>
                    @endforelse
                    
                    @if($donations->hasPages())
                        <div class="mt-4">
                            {{ $donations->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Panel Kanan: Preview & Template -->
            <div class="w-full lg:w-1/2 flex flex-col bg-[#ffffff]">
                <div class="p-5 border-b border-line shrink-0">
                    <h3 class="font-semibold text-ink">Kirim Pesan WhatsApp</h3>
                    <p class="text-xs text-muted mt-1">Pilih donatur di panel kiri, lalu pilih template pesan.</p>
                </div>
                
                <div class="flex-1 lg:overflow-y-auto p-5 relative lg:max-h-[600px]">
                    <!-- Placeholder when no donation selected -->
                    <div id="empty-selection" class="absolute inset-0 flex items-center justify-center flex-col p-8 text-center bg-[#ffffff] z-10">
                        <div class="h-16 w-16 rounded-full bg-brand-soft text-brand flex items-center justify-center mb-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <p class="text-muted font-semibold text-sm">Pilih donasi di sebelah kiri untuk mulai membalas.</p>
                    </div>

                    <!-- Action Panel -->
                    <div id="action-panel" class="hidden space-y-6">
                        <div class="rounded-xl border border-line bg-white p-4">
                            <p class="text-[10px] uppercase tracking-wider text-muted mb-2 font-semibold">Donatur Terpilih</p>
                            <p id="sel-nama" class="font-semibold text-ink text-lg">-</p>
                            <p id="sel-phone" class="text-sm text-muted mt-1">-</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-muted mb-2">Pilih Template</label>
                            <select id="template-select" class="w-full rounded-xl border border-line px-4 py-2.5 text-sm outline-none focus:border-brand bg-white">
                                <option value="">-- Pilih Template Pesan --</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->message }}">{{ $template->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-muted mb-2">Preview Pesan</label>
                            <textarea id="message-preview" rows="8" class="w-full rounded-xl border border-line px-4 py-3 text-sm outline-none focus:border-brand bg-white leading-relaxed text-ink-soft" placeholder="Isi pesan akan tampil di sini..." readonly></textarea>
                        </div>

                        <button type="button" id="btn-send" class="w-full rounded-full bg-[#25D366] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#128C7E] flex items-center justify-center gap-2 opacity-50 cursor-not-allowed" disabled>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                            </svg>
                            Kirim via WhatsApp Web
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const items = document.querySelectorAll('.donation-item');
            const emptyState = document.getElementById('empty-selection');
            const actionPanel = document.getElementById('action-panel');
            
            const selNama = document.getElementById('sel-nama');
            const selPhone = document.getElementById('sel-phone');
            const templateSelect = document.getElementById('template-select');
            const messagePreview = document.getElementById('message-preview');
            const btnSend = document.getElementById('btn-send');
            
            let currentData = null;

            items.forEach(item => {
                item.addEventListener('click', function () {
                    items.forEach(i => i.classList.remove('border-brand', 'bg-canvas'));
                    this.classList.add('border-brand', 'bg-canvas');
                    
                    currentData = JSON.parse(this.dataset.donation);
                    
                    emptyState.classList.add('hidden');
                    actionPanel.classList.remove('hidden');
                    
                    selNama.textContent = currentData.nama;
                    selPhone.textContent = currentData.phone ? `+62${currentData.phone.replace(/^0/, '')}` : 'Tidak ada nomor HP';
                    
                    updatePreview();
                });
            });

            templateSelect.addEventListener('change', updatePreview);

            function updatePreview() {
                if (!currentData || !templateSelect.value) {
                    messagePreview.value = '';
                    btnSend.disabled = true;
                    btnSend.classList.add('opacity-50', 'cursor-not-allowed');
                    return;
                }

                let message = templateSelect.value
                    .replace(/{nama}/g, currentData.nama)
                    .replace(/{campaign}/g, currentData.campaign)
                    .replace(/{jumlah}/g, currentData.jumlah);
                    
                messagePreview.value = message;

                if (currentData.phone) {
                    btnSend.disabled = false;
                    btnSend.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    btnSend.disabled = true;
                    btnSend.classList.add('opacity-50', 'cursor-not-allowed');
                    messagePreview.value += "\n\n(Peringatan: Donatur tidak memiliki nomor HP valid)";
                }
            }

            btnSend.addEventListener('click', function () {
                if (!currentData || !currentData.phone) return;
                
                let phone = currentData.phone;
                if (phone.startsWith('0')) {
                    phone = '62' + phone.substring(1);
                }
                
                const url = `https://wa.me/${phone}?text=${encodeURIComponent(messagePreview.value)}`;
                window.open(url, '_blank');
            });
        });
    </script>
    @endpush
@endsection
