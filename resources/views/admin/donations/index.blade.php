@extends('layouts.admin')

@section('content')
    <section class="rounded-[26px] bg-white shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
        <div class="flex flex-col gap-4 border-b border-[#e2e8f0] px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Modul Administrasi</p>
                <h2 class="mt-1 font-display text-3xl leading-none text-[#1e293b]">{{ $page['title'] }}</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-[#64748b]">{{ $page['description'] }}</p>
            </div>
            <div class="flex-shrink-0">
                <button
                    type="button"
                    id="open-manual-donation-modal-btn"
                    class="inline-flex items-center gap-2 rounded-full bg-pm-blue px-5 py-3 text-sm font-semibold text-white shadow-[0_10px_22px_rgba(15,92,168,0.22)] transition hover:bg-[#0c4a85]"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Donasi Manual
                </button>
            </div>
        </div>

        <div class="grid gap-4 border-b border-[#e2e8f0] px-5 py-5 lg:grid-cols-[1fr_1fr]">
            <div class="rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] px-4 py-4 text-sm leading-7 text-[#334155]">
                <p class="font-semibold text-[#1e293b]">Alur verifikasi transfer manual</p>
                <p class="mt-2">Cek mutasi Bank Syariah Nasional, cari nominal masuk yang sama dengan total transfer, cocokkan dengan referensi di tabel, lalu klik status dan isi catatan bila perlu.</p>
            </div>

            <form method="GET" action="{{ route('admin.donations') }}" class="grid gap-3 rounded-2xl border border-[#e2e8f0] bg-white px-4 py-4 sm:grid-cols-[1fr_140px_140px_120px_auto]">
                <label class="block">
                    <span class="mb-2 block text-xs font-semibold text-[#64748b]">Pencarian</span>
                    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue" placeholder="Referensi, donor...">
                </label>
                <label class="block">
                    <span class="mb-2 block text-xs font-semibold text-[#64748b]">Mulai Tanggal</span>
                    <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>
                <label class="block">
                    <span class="mb-2 block text-xs font-semibold text-[#64748b]">Sampai Tanggal</span>
                    <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                </label>
                <label class="block">
                    <span class="mb-2 block text-xs font-semibold text-[#64748b]">Status</span>
                    <select name="status" class="w-full rounded-2xl border border-[#e2e8f0] px-4 py-3 text-sm outline-none focus:border-pm-blue">
                        <option value="">Semua</option>
                        @foreach (['pending' => 'Pending', 'paid' => 'Paid', 'failed' => 'Failed', 'expired' => 'Expired', 'refunded' => 'Refunded'] as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="flex items-end gap-2">
                    <button type="submit" class="rounded-full bg-pm-blue px-4 py-3 text-sm font-semibold text-white">Filter</button>
                    <a href="{{ route('admin.donations') }}" class="rounded-full border border-[#e2e8f0] px-4 py-3 text-sm font-medium text-[#334155]">Reset</a>
                    <button type="submit" formaction="{{ route('admin.donations.export') }}" class="rounded-full bg-[#18794e] px-4 py-3 text-sm font-semibold text-white">Export Excel</button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto px-5 py-5">
            <table class="min-w-full text-left text-sm">
                <thead class="text-[#64748b]">
                    <tr class="border-b border-[#e9eff6]">
                        <th class="pb-3 font-semibold">Reference</th>
                        <th class="pb-3 font-semibold">Campaign</th>
                        <th class="pb-3 font-semibold">Donor</th>
                        <th class="pb-3 font-semibold">Transfer</th>
                        <th class="pb-3 font-semibold">Status</th>
                        <th class="pb-3 font-semibold">Dibuat</th>
                        <th class="pb-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($donations as $donation)
                        <tr class="border-b border-[#f1f5f9] last:border-b-0">
                            <td class="py-3 font-mono text-xs text-[#1e293b]">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <a href="{{ route('admin.donations.show', $donation) }}" class="text-pm-blue hover:underline font-semibold">{{ $donation->reference }}</a>
                                    @if(($donation->metadata['source'] ?? '') === 'admin_manual')
                                        <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold text-amber-800" title="Donasi diinput manual oleh Admin">Manual</span>
                                    @endif
                                    @if($donation->prayer_message)
                                        <span title="Ada pesan/doa" class="ml-1 inline-block rounded-full bg-emerald-100 p-1 text-pm-blue align-middle">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 text-[#1e293b]">{{ $donation->campaign?->title ?? '-' }}</td>
                            <td class="py-3 text-[#1e293b]">
                                <p>{{ $donation->is_anonymous ? 'Anonim' : $donation->donor_name }}</p>
                                <p class="mt-1 text-xs text-[#64748b]">{{ $donation->donor_phone ?: '-' }}</p>
                            </td>
                            <td class="py-3 text-[#1e293b]">
                                <p class="font-semibold">Rp {{ number_format((float) $donation->amount, 0, ',', '.') }}</p>
                                @if(($donation->metadata['source'] ?? '') === 'admin_manual')
                                    <p class="mt-1 text-xs font-medium text-amber-700">{{ $donation->metadata['payment_method_label'] ?? 'Manual Admin' }}</p>
                                @else
                                    <p class="mt-1 text-xs text-[#64748b]">Donasi Rp {{ number_format((float) ($donation->base_amount ?: $donation->amount), 0, ',', '.') }} + kode {{ number_format((int) $donation->unique_code, 0, ',', '.') }}</p>
                                @endif
                            </td>
                            <td class="py-3"><span class="rounded-full bg-[#eef5ff] px-3 py-1 text-xs font-semibold text-pm-blue">{{ str($donation->status)->headline() }}</span></td>
                            <td class="py-3 text-[#64748b]">
                                <p>{{ $donation->created_at->format('d M Y H:i') }}</p>
                                @if ($donation->expires_at)
                                    <p class="mt-1 text-xs">Expired {{ $donation->expires_at->format('d M Y H:i') }}</p>
                                @endif
                                @if ($donation->verified_at)
                                    <p class="mt-1 text-xs text-[#18794e]">Verifikasi {{ $donation->verified_at->format('d M Y H:i') }}</p>
                                @endif
                            </td>
                            <td class="py-3">
                                @if ($donation->status === 'pending')
                                    <form method="POST" action="{{ route('admin.donations.status', $donation) }}" class="ml-auto grid min-w-[260px] gap-2" data-status-form>
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="">
                                        <textarea name="admin_note" rows="2" class="w-full rounded-2xl border border-[#e2e8f0] px-3 py-2 text-xs outline-none focus:border-pm-blue" placeholder="Catatan admin opsional">{{ old('admin_note', $donation->admin_note) }}</textarea>
                                        <div class="flex flex-wrap justify-end gap-2">
                                            @foreach (['paid' => 'Tandai Paid', 'failed' => 'Failed', 'expired' => 'Expired'] as $status => $label)
                                                <button
                                                    type="button"
                                                    class="rounded-full border border-[#e2e8f0] px-3 py-1.5 text-xs font-medium text-[#334155] transition hover:border-[#b8cce0] hover:bg-[#f5f9fc]"
                                                    data-donation-status-action
                                                    data-donation-status="{{ $status }}"
                                                    data-donation-status-label="{{ $label }}"
                                                >
                                                    {{ $label }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </form>
                                @else
                                    <div class="min-w-[260px] space-y-2 rounded-2xl border border-[#e2e8f0] bg-[#ffffff] px-4 py-3">
                                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-[#64748b]">Status final</p>
                                        <p class="text-sm leading-6 text-[#334155]">Transaksi ini sudah selesai, jadi tombol aksi dimatikan.</p>
                                        @if ($donation->admin_note)
                                            <p class="text-xs leading-5 text-[#64748b]">Catatan: {{ $donation->admin_note }}</p>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-sm text-[#64748b]">Belum ada donasi masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-[#e2e8f0] px-5 py-4">
            {{ $donations->links() }}
        </div>
    </section>

    <div
        class="fixed inset-0 z-50 hidden items-center justify-center bg-[#1e293b]/55 px-4 py-6"
        data-status-modal
        aria-hidden="true"
    >
        <div class="w-full max-w-lg overflow-hidden rounded-[28px] border border-[#e2e8f0] bg-white shadow-[0_24px_70px_rgba(15,23,42,0.28)]">
            <div class="bg-[linear-gradient(135deg,#1e293b,#059669)] px-6 py-5 text-white">
                <p class="text-[10px] font-semibold uppercase tracking-[0.24em] text-emerald-100">Konfirmasi Verifikasi</p>
                <h3 class="mt-2 font-display text-3xl leading-none">Pastikan data mutasi sudah cocok</h3>
            </div>

            <div class="space-y-4 px-6 py-6">
                <div class="rounded-2xl border border-[#f2d58f] bg-[#fff9e8] px-4 py-4 text-sm leading-7 text-[#7b5b00]">
                    <p class="font-semibold">Sebelum melanjutkan</p>
                    <p class="mt-1">Cek mutasi rekening, pastikan nominal dan referensi sudah benar, lalu lanjutkan status transaksi ini.</p>
                </div>

                <div class="rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] px-4 py-4">
                    <p class="text-[10px] uppercase tracking-[0.14em] text-[#64748b]">Aksi yang dipilih</p>
                    <p class="mt-2 text-lg font-semibold text-[#1e293b]" data-status-modal-label>Tandai Paid</p>
                </div>

                <div class="flex flex-wrap justify-end gap-3">
                    <button type="button" class="rounded-full border border-[#e2e8f0] px-5 py-3 text-sm font-medium text-[#334155]" data-status-modal-cancel>Batal</button>
                    <button type="button" class="rounded-full bg-pm-blue px-5 py-3 text-sm font-semibold text-white shadow-[0_10px_22px_rgba(15,92,168,0.22)]" data-status-modal-confirm>Ya, lanjutkan</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Tambah Donasi Manual -->
    <div
        id="manual-donation-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-[#0f172a]/65 backdrop-blur-sm px-4 py-6 overflow-y-auto"
        aria-hidden="true"
    >
        <div class="relative w-full max-w-2xl my-auto overflow-hidden rounded-[28px] border border-[#e2e8f0] bg-white shadow-[0_25px_60px_rgba(15,23,42,0.3)]">
            <!-- Header Modal -->
            <div class="bg-[linear-gradient(135deg,#0f5ca8,#0b3d70)] px-6 py-5 text-white flex items-center justify-between">
                <div>
                    <span class="inline-block rounded-full bg-white/20 px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-[0.18em] text-white">Input Offline / Kasir</span>
                    <h3 class="mt-1 font-display text-2xl leading-tight">Tambah Donasi Manual</h3>
                    <p class="mt-1 text-xs text-blue-100">Catat donasi yang diterima langsung oleh admin tanpa notifikasi WA otomatis.</p>
                </div>
                <button
                    type="button"
                    id="close-manual-donation-modal-btn"
                    class="rounded-full bg-white/10 p-2 text-white hover:bg-white/25 transition focus:outline-none"
                    title="Tutup"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form Modal -->
            <form action="{{ route('admin.donations.manual.store') }}" method="POST" class="p-6 space-y-4 max-h-[calc(85vh-120px)] overflow-y-auto">
                @csrf

                <!-- Campaign & Nominal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[#1e293b] mb-1.5">
                            Target Campaign <span class="text-rose-500">*</span>
                        </label>
                        <select name="campaign_id" required class="w-full rounded-2xl border border-[#cbd5e1] px-3.5 py-2.5 text-sm outline-none focus:border-pm-blue focus:ring-1 focus:ring-pm-blue">
                            <option value="">-- Pilih Campaign --</option>
                            @foreach ($campaigns as $camp)
                                <option value="{{ $camp->id }}" @selected(old('campaign_id') == $camp->id)>
                                    {{ $camp->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#1e293b] mb-1.5">
                            Nominal Donasi (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="amount"
                            data-money-input
                            required
                            value="{{ old('amount') }}"
                            placeholder="Contoh: 100.000"
                            class="w-full rounded-2xl border border-[#cbd5e1] px-3.5 py-2.5 text-sm font-semibold outline-none focus:border-pm-blue focus:ring-1 focus:ring-pm-blue"
                        >
                    </div>
                </div>

                <!-- Donatur: Nama & Opsi Anonim -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[#1e293b] mb-1.5">
                            Nama Donatur <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="donor_name"
                            id="manual_donor_name"
                            required
                            value="{{ old('donor_name') }}"
                            placeholder="Nama donatur atau Hamba Allah"
                            class="w-full rounded-2xl border border-[#cbd5e1] px-3.5 py-2.5 text-sm outline-none focus:border-pm-blue focus:ring-1 focus:ring-pm-blue"
                        >
                    </div>

                    <div class="flex items-center pt-2 md:pt-6">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none text-sm text-[#334155]">
                            <input type="checkbox" name="is_anonymous" id="manual_is_anonymous" value="1" @checked(old('is_anonymous')) class="rounded border-[#cbd5e1] text-pm-blue focus:ring-pm-blue h-4 w-4">
                            <span class="font-medium">Tampilkan sebagai <strong>Anonim / Hamba Allah</strong></span>
                        </label>
                    </div>
                </div>

                <!-- Kontak: No HP & Email -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#64748b] mb-1.5">
                            No. WhatsApp / HP <span class="text-xs text-[#94a3b8] font-normal">(Opsional)</span>
                        </label>
                        <input
                            type="text"
                            name="donor_phone"
                            value="{{ old('donor_phone') }}"
                            placeholder="Contoh: 08123456789"
                            class="w-full rounded-2xl border border-[#cbd5e1] px-3.5 py-2.5 text-sm outline-none focus:border-pm-blue focus:ring-1 focus:ring-pm-blue"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#64748b] mb-1.5">
                            Email Donatur <span class="text-xs text-[#94a3b8] font-normal">(Opsional)</span>
                        </label>
                        <input
                            type="email"
                            name="donor_email"
                            value="{{ old('donor_email') }}"
                            placeholder="Contoh: donatur@email.com"
                            class="w-full rounded-2xl border border-[#cbd5e1] px-3.5 py-2.5 text-sm outline-none focus:border-pm-blue focus:ring-1 focus:ring-pm-blue"
                        >
                    </div>
                </div>

                <!-- Metode & Tanggal Donasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[#1e293b] mb-1.5">
                            Metode / Sumber Penerimaan <span class="text-rose-500">*</span>
                        </label>
                        <select name="payment_method" required class="w-full rounded-2xl border border-[#cbd5e1] px-3.5 py-2.5 text-sm outline-none focus:border-pm-blue focus:ring-1 focus:ring-pm-blue">
                            <option value="cash" @selected(old('payment_method', 'cash') === 'cash')>Tunai (Cash / Titipan)</option>
                            <option value="transfer" @selected(old('payment_method') === 'transfer')>Transfer Bank Langsung</option>
                            <option value="qris" @selected(old('payment_method') === 'qris')>QRIS Offline / Yayasan</option>
                            <option value="other" @selected(old('payment_method') === 'other')>Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#1e293b] mb-1.5">
                            Tanggal Donasi Diterima
                        </label>
                        <input
                            type="date"
                            name="donated_at"
                            value="{{ old('donated_at', now()->format('Y-m-d')) }}"
                            class="w-full rounded-2xl border border-[#cbd5e1] px-3.5 py-2.5 text-sm outline-none focus:border-pm-blue focus:ring-1 focus:ring-pm-blue"
                        >
                    </div>
                </div>

                <!-- Doa & Pesan -->
                <div>
                    <label class="block text-xs font-semibold text-[#64748b] mb-1.5">
                        Doa / Pesan Kebaikan Donatur <span class="text-xs text-[#94a3b8] font-normal">(Opsional)</span>
                    </label>
                    <textarea
                        name="prayer_message"
                        rows="2"
                        placeholder="Tuliskan doa atau pesan donatur bila ada..."
                        class="w-full rounded-2xl border border-[#cbd5e1] px-3.5 py-2.5 text-sm outline-none focus:border-pm-blue focus:ring-1 focus:ring-pm-blue"
                    >{{ old('prayer_message') }}</textarea>
                </div>

                <!-- Catatan Admin Internal -->
                <div>
                    <label class="block text-xs font-semibold text-[#64748b] mb-1.5">
                        Catatan Internal Admin <span class="text-xs text-[#94a3b8] font-normal">(Opsional)</span>
                    </label>
                    <textarea
                        name="admin_note"
                        rows="2"
                        placeholder="Contoh: Diterima tunai di kantor oleh bendahara / bukti transfer terlampir di mutasi Bank Syariah Nasional"
                        class="w-full rounded-2xl border border-[#cbd5e1] px-3.5 py-2.5 text-sm outline-none focus:border-pm-blue focus:ring-1 focus:ring-pm-blue"
                    >{{ old('admin_note') }}</textarea>
                </div>

                <!-- Tampilkan di Web Publik -->
                <div class="rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] p-3.5">
                    <label class="inline-flex items-center gap-2.5 cursor-pointer select-none text-sm text-[#334155]">
                        <input type="checkbox" name="show_on_public" value="1" @checked(old('show_on_public', true)) class="rounded border-[#cbd5e1] text-pm-blue focus:ring-pm-blue h-4 w-4">
                        <div>
                            <span class="font-semibold text-[#1e293b]">Tampilkan di website publik</span>
                            <p class="text-xs text-[#64748b]">Nama (atau Anonim) dan nominal akan muncul di daftar donatur program terkait.</p>
                        </div>
                    </label>
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-3 border-t border-[#e2e8f0] flex flex-wrap justify-end gap-3">
                    <button
                        type="button"
                        id="cancel-manual-donation-modal-btn"
                        class="rounded-full border border-[#cbd5e1] px-5 py-2.5 text-sm font-medium text-[#475569] hover:bg-slate-50 transition"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="rounded-full bg-pm-blue px-6 py-2.5 text-sm font-semibold text-white shadow-[0_10px_20px_rgba(15,92,168,0.25)] hover:bg-[#0c4a85] transition"
                    >
                        Simpan Donasi Manual
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('manual-donation-modal');
        const openBtn = document.getElementById('open-manual-donation-modal-btn');
        const closeBtn = document.getElementById('close-manual-donation-modal-btn');
        const cancelBtn = document.getElementById('cancel-manual-donation-modal-btn');
        const anonCheckbox = document.getElementById('manual_is_anonymous');
        const donorNameInput = document.getElementById('manual_donor_name');

        if (!modal) return;

        function openModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        openBtn?.addEventListener('click', openModal);
        closeBtn?.addEventListener('click', closeModal);
        cancelBtn?.addEventListener('click', closeModal);

        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });

        anonCheckbox?.addEventListener('change', function () {
            if (anonCheckbox.checked && (!donorNameInput.value || donorNameInput.value.trim() === '')) {
                donorNameInput.value = 'Hamba Allah';
            }
        });

        @if ($errors->any() && (old('campaign_id') || old('amount')))
            openModal();
        @endif
    });
</script>
@endpush
