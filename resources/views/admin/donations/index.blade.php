@extends('layouts.admin')

@section('content')
    <section class="rounded-[26px] bg-white shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
        <div class="flex flex-col gap-4 border-b border-[#e2e8f0] px-4 py-4 sm:px-5 sm:py-5 lg:flex-row lg:items-center lg:justify-between">
            <div class="min-w-0">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Modul Administrasi</p>
                <h2 class="mt-1 font-display text-2xl sm:text-3xl leading-none text-[#1e293b]">{{ $page['title'] }}</h2>
                <p class="mt-2 sm:mt-3 max-w-3xl text-xs sm:text-sm leading-6 sm:leading-7 text-[#64748b]">{{ $page['description'] }}</p>
            </div>
            <div class="grid grid-cols-1 sm:flex sm:flex-wrap items-center gap-2 sm:gap-2.5 flex-shrink-0">
                <button
                    type="button"
                    id="open-ai-analyst-modal-btn"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-full bg-[linear-gradient(135deg,#0f172a,#059669)] px-4 py-2.5 sm:px-5 sm:py-3 text-xs sm:text-sm font-semibold text-white shadow-[0_10px_24px_rgba(5,150,105,0.28)] transition hover:opacity-95"
                >
                    <svg class="h-4 w-4 shrink-0 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z" />
                    </svg>
                    Tanya AI Analis Keuangan
                </button>
                <button
                    type="button"
                    id="open-manual-donation-modal-btn"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-full bg-pm-blue px-4 py-2.5 sm:px-5 sm:py-3 text-xs sm:text-sm font-semibold text-white shadow-[0_10px_22px_rgba(15,92,168,0.22)] transition hover:bg-[#0c4a85]"
                >
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Donasi Manual
                </button>
            </div>
        </div>

        <div class="grid gap-4 border-b border-[#e2e8f0] px-4 py-4 sm:px-5 sm:py-5 xl:grid-cols-[1fr_1.35fr]">
            <div class="rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] px-4 py-3.5 sm:py-4 text-xs sm:text-sm leading-6 sm:leading-7 text-[#334155]">
                <p class="font-semibold text-[#1e293b]">Alur verifikasi transfer manual</p>
                <p class="mt-1.5 sm:mt-2">Cek mutasi Bank Syariah Nasional, cari nominal masuk yang sama dengan total transfer, cocokkan dengan referensi di tabel, lalu klik status dan isi catatan bila perlu.</p>
            </div>

            <form method="GET" action="{{ route('admin.donations') }}" class="grid grid-cols-1 gap-3 rounded-2xl border border-[#e2e8f0] bg-white px-4 py-3.5 sm:py-4 sm:grid-cols-2 lg:grid-cols-[1fr_140px_140px_120px_auto]">
                <label class="block sm:col-span-2 lg:col-span-1">
                    <span class="mb-1.5 sm:mb-2 block text-xs font-semibold text-[#64748b]">Pencarian</span>
                    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="w-full rounded-2xl border border-[#e2e8f0] px-3.5 py-2.5 sm:px-4 sm:py-3 text-xs sm:text-sm outline-none focus:border-pm-blue" placeholder="Referensi, donor...">
                </label>
                <label class="block">
                    <span class="mb-1.5 sm:mb-2 block text-xs font-semibold text-[#64748b]">Mulai Tanggal</span>
                    <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="w-full rounded-2xl border border-[#e2e8f0] px-3.5 py-2.5 sm:px-4 sm:py-3 text-xs sm:text-sm outline-none focus:border-pm-blue">
                </label>
                <label class="block">
                    <span class="mb-1.5 sm:mb-2 block text-xs font-semibold text-[#64748b]">Sampai Tanggal</span>
                    <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="w-full rounded-2xl border border-[#e2e8f0] px-3.5 py-2.5 sm:px-4 sm:py-3 text-xs sm:text-sm outline-none focus:border-pm-blue">
                </label>
                <label class="block">
                    <span class="mb-1.5 sm:mb-2 block text-xs font-semibold text-[#64748b]">Status</span>
                    <select name="status" class="w-full rounded-2xl border border-[#e2e8f0] px-3.5 py-2.5 sm:px-4 sm:py-3 text-xs sm:text-sm outline-none focus:border-pm-blue">
                        <option value="">Semua</option>
                        @foreach (['pending' => 'Pending', 'paid' => 'Paid', 'failed' => 'Failed', 'expired' => 'Expired', 'refunded' => 'Refunded'] as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="flex flex-wrap items-end gap-2 sm:col-span-2 lg:col-span-1">
                    <button type="submit" class="flex-1 sm:flex-initial rounded-full bg-pm-blue px-4 py-2.5 sm:py-3 text-xs sm:text-sm font-semibold text-white">Filter</button>
                    <a href="{{ route('admin.donations') }}" class="flex-1 sm:flex-initial text-center rounded-full border border-[#e2e8f0] px-4 py-2.5 sm:py-3 text-xs sm:text-sm font-medium text-[#334155]">Reset</a>
                    <button type="submit" formaction="{{ route('admin.donations.export') }}" class="w-full sm:w-auto rounded-full bg-[#18794e] px-4 py-2.5 sm:py-3 text-xs sm:text-sm font-semibold text-white">Export Excel</button>
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

    <!-- Modal Tanya AI Analis Laporan Keuangan (Split Workspace 2 Kolom) -->
    <div
        id="ai-analyst-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-[#0f172a]/70 backdrop-blur-sm p-2 sm:p-4 lg:p-6 overflow-y-auto"
        aria-hidden="true"
    >
        <div class="relative flex flex-col w-full max-w-6xl my-auto overflow-hidden rounded-[22px] sm:rounded-[28px] border border-[#e2e8f0] bg-white shadow-[0_25px_70px_rgba(15,23,42,0.35)] max-h-[94dvh] md:h-[86dvh] lg:h-[85vh]">
            <!-- Header Modal -->
            <div class="flex-shrink-0 bg-[linear-gradient(135deg,#0f172a,#065f46,#059669)] px-4 py-3.5 sm:px-6 sm:py-4 text-white flex items-start sm:items-center justify-between gap-3">
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-400/20 border border-emerald-300/30 px-2.5 py-0.5 sm:px-3 text-[9px] sm:text-[10px] font-semibold uppercase tracking-[0.16em] sm:tracking-[0.18em] text-emerald-100">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                            FastAPI + Google Gemini AI
                        </span>
                    </div>
                    <h3 class="mt-1 font-display text-lg sm:text-xl lg:text-2xl leading-tight">AI Analis Laporan Keuangan & Donasi</h3>
                    <p class="mt-0.5 text-[11px] sm:text-xs leading-relaxed text-emerald-100/90">Pilih topik cepat atau ketik pertanyaan untuk menganalisis arus kas, kampanye, dan retensi donatur.</p>
                </div>
                <button
                    type="button"
                    id="close-ai-analyst-modal-btn"
                    class="shrink-0 rounded-full bg-white/10 p-1.5 sm:p-2 text-white hover:bg-white/25 transition focus:outline-none"
                    title="Tutup"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body Split 2 Kolom (Mulai Tablet md:768px sudah 2 kolom sejajar, Mobile 1 kolom scroll) -->
            <div class="flex-1 min-h-0 grid grid-cols-1 md:grid-cols-[310px_1fr] lg:grid-cols-[380px_1fr] overflow-y-auto md:overflow-hidden">
                <!-- Panel Kiri: Kontrol, Filter & Prompt -->
                <div class="flex flex-col gap-3.5 sm:gap-4 border-b md:border-b-0 md:border-r border-[#e2e8f0] bg-[#f8fafc] p-4 sm:p-5 md:overflow-y-auto">
                    <!-- Konteks Filter Aktif -->
                    <div class="rounded-2xl border border-[#e2e8f0] bg-white px-3 py-2 sm:px-3.5 sm:py-2.5 text-[11px] text-[#475569]">
                        <p class="font-bold text-[#1e293b] mb-1.5">Cakupan Data Aktif:</p>
                        <div class="flex flex-wrap gap-1.5">
                            <span class="rounded-full bg-[#f1f5f9] px-2.5 py-0.5 font-medium">
                                Status: <strong>{{ !empty($filters['status']) ? strtoupper($filters['status']) : 'Semua' }}</strong>
                            </span>
                            <span class="rounded-full bg-[#f1f5f9] px-2.5 py-0.5 font-medium">
                                Periode: <strong>{{ !empty($filters['start_date']) || !empty($filters['end_date']) ? (($filters['start_date'] ?? 'Awal') . ' s/d ' . ($filters['end_date'] ?? 'Sekarang')) : 'Semua (180 Hari)' }}</strong>
                            </span>
                            @if(!empty($filters['q']))
                                <span class="rounded-full bg-[#f1f5f9] px-2.5 py-0.5 font-medium">
                                    Cari: <strong>"{{ $filters['q'] }}"</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Pilihan Pertanyaan Cepat (Quick Prompts) -->
                    <div>
                        <p class="text-[10px] sm:text-[11px] font-bold uppercase tracking-[0.14em] text-[#64748b] mb-2">Topik Analisis Cepat</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 gap-2">
                            <button
                                type="button"
                                data-ai-quick-prompt="Berikan Ringkasan Eksekutif arus kas donasi saat ini, tren bulanan, kontribusi pola Jumat Berkah serta periode gajian (tanggal 25-5), dan rekomendasi peningkatan penghimpunan bulan depan."
                                class="w-full text-left rounded-xl border border-[#e2e8f0] bg-white hover:border-emerald-500 hover:bg-emerald-50/40 px-3 py-2 sm:px-3.5 sm:py-2.5 transition group"
                            >
                                <p class="text-xs font-bold text-[#1e293b] group-hover:text-emerald-800">1. Ringkasan Eksekutif & Arus Kas</p>
                                <p class="mt-0.5 text-[11px] text-[#64748b] line-clamp-1">Tren kas masuk, kode unik, Jumat Berkah & gajian.</p>
                            </button>

                            <button
                                type="button"
                                data-ai-quick-prompt="Identifikasi program kampanye yang berstatus kritis (sisa hari sedikit namun capaian masih rendah) dibandingkan kampanye yang hampir/sudah 100%, lalu berikan strategi konkret untuk menyelamatkan target kampanye kritis tersebut."
                                class="w-full text-left rounded-xl border border-[#e2e8f0] bg-white hover:border-emerald-500 hover:bg-emerald-50/40 px-3 py-2 sm:px-3.5 sm:py-2.5 transition group"
                            >
                                <p class="text-xs font-bold text-[#1e293b] group-hover:text-emerald-800">2. Kampanye Kritis & Strategi Boost</p>
                                <p class="mt-0.5 text-[11px] text-[#64748b] line-clamp-1">Deteksi dini program mendesak sebelum tenggat habis.</p>
                            </button>

                            <button
                                type="button"
                                data-ai-quick-prompt="Analisis rasio konversi pembayaran (paid vs pending, expired, failed) di setiap kanal pembayaran (QRIS, Transfer Bank Manual, Virtual Account, E-Wallet, Kasir Manual). Mengapa ada transaksi expired/pending dan bagaimana cara menekannya?"
                                class="w-full text-left rounded-xl border border-[#e2e8f0] bg-white hover:border-emerald-500 hover:bg-emerald-50/40 px-3 py-2 sm:px-3.5 sm:py-2.5 transition group"
                            >
                                <p class="text-xs font-bold text-[#1e293b] group-hover:text-emerald-800">3. Konversi & Kebocoran Pembayaran</p>
                                <p class="mt-0.5 text-[11px] text-[#64748b] line-clamp-1">Efektivitas QRIS, VA & Transfer Manual + solusi expired.</p>
                            </button>

                            <button
                                type="button"
                                data-ai-quick-prompt="Analisis struktur segmentasi donatur kita (proporsi Donatur VIP >= Rp 5 juta, tingkat retensi Repeat Donor, rata-rata nominal per transaksi, dan kebiasaan donatur anonim). Berikan program retensi terbaik untuk menjaga loyalitas mereka."
                                class="w-full text-left rounded-xl border border-[#e2e8f0] bg-white hover:border-emerald-500 hover:bg-emerald-50/40 px-3 py-2 sm:px-3.5 sm:py-2.5 transition group"
                            >
                                <p class="text-xs font-bold text-[#1e293b] group-hover:text-emerald-800">4. Segmentasi Donatur VIP & Retensi</p>
                                <p class="mt-0.5 text-[11px] text-[#64748b] line-clamp-1">Kontribusi donatur utama, repeat rate & strategi CRM.</p>
                            </button>
                        </div>
                    </div>

                    <!-- Input Pertanyaan Kustom -->
                    <form id="ai-analyst-form" class="space-y-3 mt-auto pt-2 border-t border-[#e2e8f0]">
                        <div>
                            <label for="ai-question-input" class="block text-xs font-bold text-[#1e293b] mb-1.5">
                                Pertanyaan ke AI
                            </label>
                            <textarea
                                id="ai-question-input"
                                name="question"
                                rows="3"
                                required
                                placeholder="Ketik pertanyaan spesifik Anda..."
                                class="w-full rounded-2xl border border-[#cbd5e1] bg-white px-3 py-2 sm:px-3.5 sm:py-2.5 text-xs leading-relaxed outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600"
                            >Berikan Ringkasan Eksekutif arus kas donasi saat ini, tren bulanan, kontribusi pola Jumat Berkah serta periode gajian (tanggal 25-5), dan rekomendasi peningkatan penghimpunan bulan depan.</textarea>
                        </div>

                        <button
                            type="submit"
                            id="ai-submit-btn"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-full bg-[#059669] px-5 py-2.5 text-xs sm:text-sm font-semibold text-white shadow-[0_10px_22px_rgba(5,150,105,0.28)] hover:bg-[#047857] transition disabled:opacity-60"
                        >
                            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span id="ai-submit-btn-text">Analisis Sekarang</span>
                        </button>
                    </form>
                </div>

                <!-- Panel Kanan: Layar Hasil Analisis -->
                <div id="ai-right-panel" class="p-4 sm:p-5 lg:p-6 bg-white md:overflow-y-auto min-w-0">
                    <!-- State Kosong Awal -->
                    <div id="ai-empty-state" class="flex h-full min-h-[200px] sm:min-h-[260px] flex-col items-center justify-center rounded-2xl border border-dashed border-[#cbd5e1] bg-[#f8fafc]/60 p-5 sm:p-8 text-center">
                        <div class="flex h-10 w-10 sm:h-12 sm:w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h4 class="mt-3 sm:mt-4 text-xs sm:text-sm font-bold text-[#1e293b]">Siap Menganalisis Laporan Keuangan</h4>
                        <p class="mt-1.5 max-w-md text-[11px] sm:text-xs leading-relaxed text-[#64748b]">
                            Pilih salah satu dari 4 topik analisis cepat atau tulis pertanyaan Anda sendiri, lalu klik <strong>Analisis Sekarang</strong>. Ringkasan KPI, tabel data, dan rekomendasi akan langsung tampil di area ini.
                        </p>
                    </div>

                    <!-- State Loading -->
                    <div id="ai-loading-state" class="hidden flex h-full min-h-[200px] sm:min-h-[260px] flex-col items-center justify-center rounded-2xl border border-emerald-200 bg-emerald-50/50 p-5 sm:p-8 text-center">
                        <div class="inline-flex h-10 w-10 sm:h-11 sm:w-11 animate-spin items-center justify-center rounded-full border-4 border-emerald-200 border-t-emerald-600"></div>
                        <p class="mt-3 sm:mt-4 text-xs sm:text-sm font-bold text-[#065f46]">AI Analis sedang membedah data laporan keuangan & kampanye...</p>
                        <p class="mt-1 text-[11px] sm:text-xs text-[#047857]">Menghitung metrik agregat Laravel & meminta insight strategis dari Google Gemini.</p>
                    </div>

                    <!-- State Error -->
                    <div id="ai-error-state" class="hidden rounded-2xl border border-rose-200 bg-rose-50 p-4 sm:p-5 text-xs sm:text-sm text-rose-800">
                        <p class="font-bold">Gagal Memproses Analisis AI</p>
                        <p id="ai-error-message" class="mt-1 text-xs leading-relaxed break-words"></p>
                    </div>

                    <!-- Hasil Analisis AI -->
                    <div id="ai-result-container" class="hidden rounded-2xl border border-[#e2e8f0] bg-white overflow-hidden shadow-xs">
                        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-[#e2e8f0] bg-[#f8fafc] px-3.5 py-2.5 sm:px-5 sm:py-3">
                            <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 min-w-0">
                                <span class="h-2 w-2 sm:h-2.5 sm:w-2.5 shrink-0 rounded-full bg-emerald-500"></span>
                                <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-[#1e293b]">Hasil Laporan Analis AI</span>
                                <span id="ai-model-badge" class="rounded-full bg-emerald-100 px-2 py-0.5 sm:px-2.5 text-[10px] sm:text-[11px] font-semibold text-emerald-800"></span>
                            </div>
                            <button
                                type="button"
                                id="ai-copy-btn"
                                class="inline-flex items-center gap-1.5 rounded-full border border-[#cbd5e1] bg-white px-2.5 py-1 sm:px-3 text-[11px] sm:text-xs font-medium text-[#334155] hover:bg-slate-50 transition"
                            >
                                Salin Laporan
                            </button>
                        </div>
                        <div id="ai-kpi-strip" class="hidden grid grid-cols-2 xl:grid-cols-4 gap-2 sm:gap-2.5 border-b border-[#e2e8f0] bg-white px-3.5 py-3 sm:px-5 sm:py-3.5">
                            <div class="rounded-xl border border-[#e2e8f0] bg-[#f8fafc] px-3 py-2 sm:py-2.5 min-w-0">
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-[#64748b]">Kas Masuk (Paid)</p>
                                <p id="ai-kpi-cash" class="mt-0.5 sm:mt-1 text-xs sm:text-sm font-bold text-[#065f46] break-words">-</p>
                            </div>
                            <div class="rounded-xl border border-[#e2e8f0] bg-[#f8fafc] px-3 py-2 sm:py-2.5 min-w-0">
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-[#64748b]">Transaksi Sukses</p>
                                <p id="ai-kpi-tx" class="mt-0.5 sm:mt-1 text-xs sm:text-sm font-bold text-[#1e293b]">-</p>
                            </div>
                            <div class="rounded-xl border border-[#e2e8f0] bg-[#f8fafc] px-3 py-2 sm:py-2.5 min-w-0">
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-[#64748b]">Konversi Paid</p>
                                <p id="ai-kpi-conv" class="mt-0.5 sm:mt-1 text-xs sm:text-sm font-bold text-pm-blue">-</p>
                            </div>
                            <div class="rounded-xl border border-[#e2e8f0] bg-[#f8fafc] px-3 py-2 sm:py-2.5 min-w-0">
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-[#64748b]">Kampanye Kritis</p>
                                <p id="ai-kpi-crit" class="mt-0.5 sm:mt-1 text-xs sm:text-sm font-bold text-rose-600">-</p>
                            </div>
                        </div>
                        <div id="ai-result-content" class="p-3.5 sm:p-5 text-xs sm:text-sm leading-6 sm:leading-7 text-[#1e293b] space-y-2.5 sm:space-y-3 bg-white"></div>
                    </div>
                </div>
            </div>
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

        // === Logika Modal AI Analis Keuangan ===
        const aiModal = document.getElementById('ai-analyst-modal');
        const openAiBtn = document.getElementById('open-ai-analyst-modal-btn');
        const closeAiBtn = document.getElementById('close-ai-analyst-modal-btn');
        const aiForm = document.getElementById('ai-analyst-form');
        const aiQuestionInput = document.getElementById('ai-question-input');
        const aiSubmitBtn = document.getElementById('ai-submit-btn');
        const aiSubmitBtnText = document.getElementById('ai-submit-btn-text');
        const aiLoadingState = document.getElementById('ai-loading-state');
        const aiEmptyState = document.getElementById('ai-empty-state');
        const aiRightPanel = document.getElementById('ai-right-panel');
        const aiErrorState = document.getElementById('ai-error-state');
        const aiErrorMessage = document.getElementById('ai-error-message');
        const aiResultContainer = document.getElementById('ai-result-container');
        const aiResultContent = document.getElementById('ai-result-content');
        const aiModelBadge = document.getElementById('ai-model-badge');
        const aiCopyBtn = document.getElementById('ai-copy-btn');
        let lastRawMarkdown = '';

        function openAiModal() {
            if (!aiModal) return;
            aiModal.classList.remove('hidden');
            aiModal.classList.add('flex');
            aiModal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeAiModal() {
            if (!aiModal) return;
            aiModal.classList.add('hidden');
            aiModal.classList.remove('flex');
            aiModal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        openAiBtn?.addEventListener('click', openAiModal);
        closeAiBtn?.addEventListener('click', closeAiModal);

        aiModal?.addEventListener('click', function (e) {
            if (e.target === aiModal) {
                closeAiModal();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && aiModal && !aiModal.classList.contains('hidden')) {
                closeAiModal();
            }
        });

        // Quick prompt buttons
        const quickPromptBtns = document.querySelectorAll('[data-ai-quick-prompt]');
        quickPromptBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                quickPromptBtns.forEach(b => b.classList.remove('border-emerald-500', 'bg-emerald-50/70', 'ring-1', 'ring-emerald-500'));
                btn.classList.add('border-emerald-500', 'bg-emerald-50/70', 'ring-1', 'ring-emerald-500');
                const promptText = btn.getAttribute('data-ai-quick-prompt') || '';
                if (aiQuestionInput) {
                    aiQuestionInput.value = promptText;
                    aiQuestionInput.focus();
                }
            });
        });

        function escapeHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function formatInlineMarkdown(text) {
            let safe = escapeHtml(text);
            safe = safe.replace(/\*\*(.+?)\*\*/g, '<strong class="font-bold text-[#0f172a]">$1</strong>');
            safe = safe.replace(/\*(.+?)\*/g, '<em>$1</em>');
            safe = safe.replace(/`([^`]+)`/g, '<code class="rounded bg-slate-200/80 px-1.5 py-0.5 font-mono text-xs text-emerald-900">$1</code>');
            return safe;
        }

        function parseTableCells(rowLine) {
            return rowLine
                .trim()
                .replace(/^\|/, '')
                .replace(/\|$/, '')
                .split('|')
                .map(cell => cell.trim());
        }

        function isTableSeparator(line) {
            return /^\|?(\s*:?-+:?\s*\|)+\s*:?-+:?\s*\|?$/.test(line.trim());
        }

        function renderMarkdownToHtml(markdown) {
            const lines = String(markdown || '').split(/\r?\n/);
            let html = '';
            let listType = null;

            function closeList() {
                if (listType) {
                    html += listType === 'ol' ? '</ol>' : '</ul>';
                    listType = null;
                }
            }

            for (let i = 0; i < lines.length; i++) {
                const line = lines[i].trim();

                if (!line) {
                    closeList();
                    continue;
                }

                // Markdown Table detection
                if (line.includes('|') && i + 1 < lines.length && isTableSeparator(lines[i + 1])) {
                    closeList();
                    const headers = parseTableCells(line);
                    i += 2; // skip header and separator
                    html += '<div class="my-2.5 sm:my-3 overflow-x-auto rounded-xl border border-[#e2e8f0] shadow-xs"><table class="min-w-full text-left text-[11px] sm:text-xs border-collapse">';
                    html += '<thead class="bg-[#f8fafc] text-[#475569] border-b border-[#e2e8f0]"><tr>';
                    headers.forEach(h => {
                        html += '<th class="px-2.5 py-2 sm:px-3.5 sm:py-2.5 font-bold uppercase tracking-wider text-[10px] sm:text-[11px] whitespace-nowrap">' + formatInlineMarkdown(h) + '</th>';
                    });
                    html += '</tr></thead><tbody class="divide-y divide-[#f1f5f9] bg-white">';

                    while (i < lines.length && lines[i].trim().includes('|') && !isTableSeparator(lines[i])) {
                        const cells = parseTableCells(lines[i]);
                        html += '<tr class="hover:bg-[#f8fafc] transition-colors">';
                        for (let c = 0; c < headers.length; c++) {
                            html += '<td class="px-2.5 py-2 sm:px-3.5 sm:py-2.5 text-[#334155] align-top">' + formatInlineMarkdown(cells[c] || '') + '</td>';
                        }
                        html += '</tr>';
                        i++;
                    }
                    i--; // adjust for loop increment
                    html += '</tbody></table></div>';
                    continue;
                }

                // Blockquote Callout
                if (line.startsWith('> ')) {
                    closeList();
                    const quoteLines = [line.slice(2)];
                    while (i + 1 < lines.length && lines[i + 1].trim().startsWith('> ')) {
                        i++;
                        quoteLines.push(lines[i].trim().slice(2));
                    }
                    html += '<div class="my-2.5 sm:my-3 rounded-xl sm:rounded-2xl border-l-4 border-emerald-600 bg-emerald-50/70 px-3 py-2.5 sm:px-4 sm:py-3 text-xs sm:text-sm leading-relaxed text-[#064e3b]">'
                        + quoteLines.map(q => formatInlineMarkdown(q)).join('<br>')
                        + '</div>';
                    continue;
                }

                if (/^---+$/.test(line)) {
                    closeList();
                    html += '<hr class="my-2.5 sm:my-3 border-[#e2e8f0]">';
                    continue;
                }

                if (line.startsWith('### ')) {
                    closeList();
                    html += '<h4 class="mt-3.5 sm:mt-4 mb-1.5 sm:mb-2 flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm font-bold uppercase tracking-wide text-[#0f172a]">' + formatInlineMarkdown(line.slice(4)) + '</h4>';
                } else if (line.startsWith('## ')) {
                    closeList();
                    html += '<h3 class="mt-3.5 sm:mt-4 mb-1.5 sm:mb-2 text-sm sm:text-base font-bold text-[#065f46] border-b border-emerald-100 pb-1.5">' + formatInlineMarkdown(line.slice(3)) + '</h3>';
                } else if (line.startsWith('# ')) {
                    closeList();
                    html += '<h2 class="mt-3.5 sm:mt-4 mb-1.5 sm:mb-2 text-base sm:text-lg font-bold text-[#0f172a]">' + formatInlineMarkdown(line.slice(2)) + '</h2>';
                } else if (/^(\*|-)\s+/.test(line)) {
                    if (listType !== 'ul') {
                        closeList();
                        html += '<ul class="list-disc pl-4 sm:pl-5 space-y-1 sm:space-y-1.5 my-2">';
                        listType = 'ul';
                    }
                    html += '<li class="text-xs sm:text-sm leading-relaxed text-[#334155]">' + formatInlineMarkdown(line.replace(/^(\*|-)\s+/, '')) + '</li>';
                } else if (/^\d+\.\s+/.test(line)) {
                    if (listType !== 'ol') {
                        closeList();
                        html += '<ol class="list-decimal pl-4 sm:pl-5 space-y-1 sm:space-y-1.5 my-2 marker:font-bold marker:text-emerald-700">';
                        listType = 'ol';
                    }
                    html += '<li class="text-xs sm:text-sm leading-relaxed text-[#334155]">' + formatInlineMarkdown(line.replace(/^\d+\.\s+/, '')) + '</li>';
                } else {
                    closeList();
                    html += '<p class="text-xs sm:text-sm leading-relaxed text-[#334155]">' + formatInlineMarkdown(line) + '</p>';
                }
            }

            closeList();
            return html;
        }

        aiForm?.addEventListener('submit', async function (e) {
            e.preventDefault();
            const question = (aiQuestionInput?.value || '').trim();
            if (!question) return;

            aiEmptyState?.classList.add('hidden');
            aiLoadingState?.classList.remove('hidden');
            aiErrorState?.classList.add('hidden');
            aiResultContainer?.classList.add('hidden');
            if (aiSubmitBtn) aiSubmitBtn.disabled = true;
            if (aiSubmitBtnText) aiSubmitBtnText.textContent = 'Menganalisis...';
            if (window.innerWidth < 768 && aiRightPanel) {
                aiRightPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            try {
                const response = await fetch(@json(route('admin.ai-analyst.ask')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token()),
                    },
                    body: JSON.stringify({
                        question: question,
                        status: @json($filters['status'] ?? null),
                        q: @json($filters['q'] ?? null),
                        start_date: @json($filters['start_date'] ?? null),
                        end_date: @json($filters['end_date'] ?? null),
                    }),
                });

                const data = await response.json();

                if (!response.ok || data.status !== 'success') {
                    throw new Error(data.message || 'Gagal memperoleh respons dari layanan AI.');
                }

                lastRawMarkdown = data.ai_response || '';
                if (aiModelBadge) {
                    aiModelBadge.textContent = data.model_used || 'Gemini AI';
                }

                const kpiStrip = document.getElementById('ai-kpi-strip');
                if (data.context_summary && kpiStrip) {
                    const fmtRp = (val) => 'Rp ' + Number(val || 0).toLocaleString('id-ID');
                    document.getElementById('ai-kpi-cash').textContent = fmtRp(data.context_summary.filtered_paid_cash_in);
                    document.getElementById('ai-kpi-tx').textContent = `${data.context_summary.filtered_paid_tx || 0} / ${data.context_summary.filtered_total_rows || 0}`;
                    document.getElementById('ai-kpi-conv').textContent = `${data.context_summary.conversion_rate_pct || 0}%`;
                    document.getElementById('ai-kpi-crit').textContent = `${data.context_summary.critical_campaigns_count || 0} Program`;
                    kpiStrip.classList.remove('hidden');
                }

                if (aiResultContent) {
                    aiResultContent.innerHTML = renderMarkdownToHtml(lastRawMarkdown);
                }
                aiResultContainer?.classList.remove('hidden');
            } catch (err) {
                if (aiErrorMessage) {
                    aiErrorMessage.textContent = err.message || 'Terjadi kesalahan jaringan saat menghubungi server AI.';
                }
                aiErrorState?.classList.remove('hidden');
            } finally {
                aiLoadingState?.classList.add('hidden');
                if (aiSubmitBtn) aiSubmitBtn.disabled = false;
                if (aiSubmitBtnText) aiSubmitBtnText.textContent = 'Analisis Sekarang';
            }
        });

        aiCopyBtn?.addEventListener('click', function () {
            if (!lastRawMarkdown) return;
            navigator.clipboard.writeText(lastRawMarkdown).then(function () {
                const originalText = aiCopyBtn.textContent;
                aiCopyBtn.textContent = 'Tersalin!';
                setTimeout(function () {
                    aiCopyBtn.textContent = originalText;
                }, 1800);
            });
        });

        // Buka otomatis jika ada parameter ?open_ai=1
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('open_ai') === '1') {
            openAiModal();
        }
    });
</script>
@endpush
