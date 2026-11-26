@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        {{-- Top Action & Filter Card --}}
        <section class="overflow-hidden rounded-xl border border-line bg-surface">
            <div class="flex flex-col gap-4 border-b border-line px-5 py-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="min-w-0">
                    <h2 class="font-display text-2xl font-semibold leading-none text-ink">{{ $page['title'] }}</h2>
                    <p class="mt-1.5 max-w-2xl text-xs leading-6 text-muted sm:text-sm">{{ $page['description'] }}</p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <button
                        type="button"
                        id="open-ai-analyst-modal-btn"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-brand/30 bg-brand-soft px-4 py-2 text-xs font-semibold text-brand transition-colors hover:bg-brand hover:text-white sm:text-sm"
                    >
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Analis laporan keuangan
                    </button>

                    <button
                        type="button"
                        id="open-manual-donation-modal-btn"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand px-4 py-2 text-xs font-semibold text-white transition-colors hover:bg-brand-deep sm:text-sm"
                    >
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah donasi manual
                    </button>
                </div>
            </div>

            {{-- Verification Note + Filter Bar --}}
            <div class="grid gap-4 border-b border-line bg-canvas px-5 py-4 xl:grid-cols-[0.85fr_1.45fr] xl:items-center">
                <div class="rounded-lg border border-line bg-surface px-4 py-3 text-xs leading-5 text-muted">
                    <p class="font-semibold text-ink">Alur verifikasi transfer manual</p>
                    <p class="mt-1">Cocokkan nominal unik pada mutasi Bank Syariah Nasional dengan referensi di tabel, lalu ubah status transaksi.</p>
                </div>

                <form method="GET" action="{{ route('admin.donations') }}" class="grid grid-cols-1 gap-2.5 sm:grid-cols-2 lg:grid-cols-[1fr_135px_135px_115px_auto] lg:items-end">
                    <label class="block sm:col-span-2 lg:col-span-1">
                        <span class="mb-1 block text-[11px] font-medium text-muted">Pencarian</span>
                        <input
                            type="text"
                            name="q"
                            value="{{ $filters['q'] ?? '' }}"
                            class="w-full rounded-lg border border-line-strong bg-surface px-3 py-2 text-xs text-ink outline-none focus:border-brand"
                            placeholder="Referensi, donatur..."
                        >
                    </label>
                    <label class="block">
                        <span class="mb-1 block text-[11px] font-medium text-muted">Mulai tanggal</span>
                        <input
                            type="date"
                            name="start_date"
                            value="{{ $filters['start_date'] ?? '' }}"
                            class="w-full rounded-lg border border-line-strong bg-surface px-3 py-2 text-xs text-ink outline-none focus:border-brand"
                        >
                    </label>
                    <label class="block">
                        <span class="mb-1 block text-[11px] font-medium text-muted">Sampai tanggal</span>
                        <input
                            type="date"
                            name="end_date"
                            value="{{ $filters['end_date'] ?? '' }}"
                            class="w-full rounded-lg border border-line-strong bg-surface px-3 py-2 text-xs text-ink outline-none focus:border-brand"
                        >
                    </label>
                    <label class="block">
                        <span class="mb-1 block text-[11px] font-medium text-muted">Status</span>
                        <select name="status" class="w-full rounded-lg border border-line-strong bg-surface px-3 py-2 text-xs text-ink outline-none focus:border-brand">
                            <option value="">Semua</option>
                            @foreach (['pending' => 'Pending', 'paid' => 'Paid', 'failed' => 'Failed', 'expired' => 'Expired', 'refunded' => 'Refunded'] as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                    <div class="flex flex-wrap items-center gap-1.5 sm:col-span-2 lg:col-span-1">
                        <button type="submit" class="rounded-lg bg-ink px-3.5 py-2 text-xs font-semibold text-white transition-colors hover:bg-ink-soft">Filter</button>
                        <a href="{{ route('admin.donations') }}" class="rounded-lg border border-line-strong bg-surface px-3 py-2 text-xs font-medium text-ink-soft hover:bg-surface-subtle">Reset</a>
                        <button type="submit" formaction="{{ route('admin.donations.export') }}" class="rounded-lg border border-brand/30 bg-brand-soft px-3 py-2 text-xs font-semibold text-brand hover:bg-brand hover:text-white transition-colors">
                            Export Excel
                        </button>
                    </div>
                </form>
            </div>

            {{-- Donations Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-xs sm:text-sm tabular-nums">
                    <thead>
                        <tr class="border-b border-line bg-canvas text-xs text-muted">
                            <th class="px-5 py-3 font-medium">Referensi</th>
                            <th class="px-5 py-3 font-medium">Program</th>
                            <th class="px-5 py-3 font-medium">Donatur</th>
                            <th class="px-5 py-3 font-medium">Transfer</th>
                            <th class="px-5 py-3 font-medium">Status</th>
                            <th class="px-5 py-3 font-medium">Waktu</th>
                            <th class="px-5 py-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @forelse ($donations as $donation)
                            @php
                                $badgeClass = match($donation->status) {
                                    'paid' => 'bg-brand-soft text-brand',
                                    'pending' => 'bg-ochre-soft text-ochre-ink',
                                    'failed' => 'bg-danger-soft text-danger',
                                    default => 'bg-surface-subtle text-muted',
                                };
                            @endphp
                            <tr class="transition-colors hover:bg-canvas/60">
                                <td class="px-5 py-3.5 font-mono text-xs text-ink align-top">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <a href="{{ route('admin.donations.show', $donation) }}" class="font-semibold text-brand hover:underline">
                                            {{ $donation->reference }}
                                        </a>
                                        @if(($donation->metadata['source'] ?? '') === 'admin_manual')
                                            <span class="rounded bg-ochre-soft px-1.5 py-0.5 font-sans text-[10px] font-semibold text-ochre-ink" title="Diinput manual oleh Admin">Manual</span>
                                        @endif
                                        @if($donation->prayer_message)
                                            <span title="Ada pesan/doa" class="inline-flex rounded bg-brand-soft p-1 text-brand">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-ink align-top">{{ $donation->campaign?->title ?? '-' }}</td>
                                <td class="px-5 py-3.5 align-top">
                                    <p class="font-medium text-ink">{{ $donation->is_anonymous ? 'Anonim' : $donation->donor_name }}</p>
                                    <p class="mt-0.5 text-xs text-muted">{{ $donation->donor_phone ?: '-' }}</p>
                                </td>
                                <td class="px-5 py-3.5 align-top">
                                    <p class="font-semibold text-ink">Rp {{ number_format((float) $donation->amount, 0, ',', '.') }}</p>
                                    @if(($donation->metadata['source'] ?? '') === 'admin_manual')
                                        <p class="mt-0.5 text-xs text-ochre-ink">{{ $donation->metadata['payment_method_label'] ?? 'Manual Admin' }}</p>
                                    @else
                                        <p class="mt-0.5 text-xs text-muted">Pokok Rp {{ number_format((float) ($donation->base_amount ?: $donation->amount), 0, ',', '.') }} + kode {{ number_format((int) $donation->unique_code, 0, ',', '.') }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 align-top">
                                    <span class="inline-flex rounded-md px-2.5 py-0.5 text-xs font-semibold {{ $badgeClass }}">
                                        {{ str($donation->status)->headline() }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-muted align-top">
                                    <p class="text-ink">{{ $donation->created_at->format('d M Y H:i') }}</p>
                                    @if ($donation->expires_at)
                                        <p class="mt-0.5">Exp. {{ $donation->expires_at->format('d M Y H:i') }}</p>
                                    @endif
                                    @if ($donation->verified_at)
                                        <p class="mt-0.5 text-brand">Verif. {{ $donation->verified_at->format('d M Y H:i') }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 align-top">
                                    @if ($donation->status === 'pending')
                                        <form method="POST" action="{{ route('admin.donations.status', $donation) }}" class="ml-auto grid min-w-[240px] gap-2" data-status-form>
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="">
                                            <textarea
                                                name="admin_note"
                                                rows="2"
                                                class="w-full rounded-lg border border-line-strong bg-canvas px-2.5 py-1.5 text-xs text-ink outline-none focus:border-brand focus:bg-surface"
                                                placeholder="Catatan admin opsional"
                                            >{{ old('admin_note', $donation->admin_note) }}</textarea>
                                            <div class="flex flex-wrap justify-end gap-1.5">
                                                @foreach (['paid' => 'Tandai Paid', 'failed' => 'Failed', 'expired' => 'Expired'] as $status => $label)
                                                    <button
                                                        type="button"
                                                        class="rounded-md border px-2.5 py-1 text-xs font-medium transition-colors {{ $status === 'paid' ? 'border-brand/30 bg-brand-soft text-brand hover:bg-brand hover:text-white' : 'border-line-strong bg-surface text-ink-soft hover:bg-surface-subtle' }}"
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
                                        <div class="ml-auto min-w-[220px] rounded-lg border border-line bg-canvas px-3 py-2.5 text-xs">
                                            <p class="font-semibold text-ink">Status final</p>
                                            <p class="mt-0.5 text-muted">Transaksi selesai diverifikasi.</p>
                                            @if ($donation->admin_note)
                                                <p class="mt-1 border-t border-line pt-1 text-muted">Catatan: {{ $donation->admin_note }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-sm text-muted">Belum ada donasi masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-line px-5 py-4">
                {{ $donations->links() }}
            </div>
        </section>
    </div>

    {{-- Modal Konfirmasi Status --}}
    <div
        class="fixed inset-0 z-50 hidden items-center justify-center bg-ink/50 px-4 py-6 backdrop-blur-[1px]"
        data-status-modal
        aria-hidden="true"
    >
        <div class="w-full max-w-md overflow-hidden rounded-xl border border-line bg-surface">
            <div class="border-b border-line bg-canvas px-6 py-4">
                <p class="text-xs font-medium text-brand">Konfirmasi verifikasi</p>
                <h3 class="mt-1 font-display text-2xl font-semibold text-ink">Pastikan data mutasi cocok</h3>
            </div>

            <div class="space-y-4 px-6 py-5">
                <div class="rounded-lg border border-ochre/30 bg-ochre-soft px-4 py-3 text-xs leading-6 text-ochre-ink">
                    <p class="font-semibold">Sebelum melanjutkan</p>
                    <p class="mt-0.5">Cek mutasi rekening, pastikan nominal dan nomor referensi sudah sesuai, lalu konfirmasi perubahan status.</p>
                </div>

                <div class="rounded-lg border border-line bg-canvas px-4 py-3">
                    <p class="text-xs text-muted">Aksi yang dipilih</p>
                    <p class="mt-1 text-base font-semibold text-ink" data-status-modal-label>Tandai Paid</p>
                </div>

                <div class="flex flex-wrap justify-end gap-2.5 pt-2">
                    <button type="button" class="rounded-lg border border-line-strong bg-surface px-4 py-2 text-xs font-medium text-ink-soft hover:bg-surface-subtle" data-status-modal-cancel>Batal</button>
                    <button type="button" class="rounded-lg bg-brand px-4 py-2 text-xs font-semibold text-white hover:bg-brand-deep" data-status-modal-confirm>Ya, lanjutkan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah Donasi Manual --}}
    <div
        id="manual-donation-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto bg-ink/50 px-4 py-6 backdrop-blur-[1px]"
        aria-hidden="true"
    >
        <div class="relative my-auto w-full max-w-2xl overflow-hidden rounded-xl border border-line bg-surface">
            <div class="flex items-start justify-between gap-4 border-b border-line bg-canvas px-6 py-4">
                <div>
                    <p class="text-xs font-medium text-brand">Input offline / kasir</p>
                    <h3 class="mt-0.5 font-display text-2xl font-semibold text-ink">Tambah donasi manual</h3>
                    <p class="mt-1 text-xs text-muted">Catat donasi yang diterima langsung oleh admin tanpa notifikasi WhatsApp otomatis.</p>
                </div>
                <button
                    type="button"
                    id="close-manual-donation-modal-btn"
                    class="rounded-md border border-line bg-surface p-1.5 text-muted hover:text-ink"
                    title="Tutup"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('admin.donations.manual.store') }}" method="POST" class="max-h-[calc(85vh-110px)] space-y-4 overflow-y-auto p-6">
                @csrf

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-ink">
                            Target campaign <span class="text-danger">*</span>
                        </label>
                        <select name="campaign_id" required class="w-full rounded-lg border border-line-strong bg-canvas px-3 py-2 text-sm text-ink outline-none focus:border-brand focus:bg-surface">
                            <option value="">-- Pilih campaign --</option>
                            @foreach ($campaigns as $camp)
                                <option value="{{ $camp->id }}" @selected(old('campaign_id') == $camp->id)>{{ $camp->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-ink">
                            Nominal donasi (Rp) <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="amount"
                            data-money-input
                            required
                            value="{{ old('amount') }}"
                            placeholder="100.000"
                            class="w-full rounded-lg border border-line-strong bg-canvas px-3 py-2 text-sm font-semibold text-ink tabular-nums outline-none focus:border-brand focus:bg-surface"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-ink">
                            Nama donatur <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="donor_name"
                            id="manual_donor_name"
                            required
                            value="{{ old('donor_name') }}"
                            placeholder="Nama donatur atau Hamba Allah"
                            class="w-full rounded-lg border border-line-strong bg-canvas px-3 py-2 text-sm text-ink outline-none focus:border-brand focus:bg-surface"
                        >
                    </div>

                    <div class="flex items-center pt-1 md:pt-5">
                        <label class="inline-flex cursor-pointer items-center gap-2 text-xs text-ink-soft select-none">
                            <input type="checkbox" name="is_anonymous" id="manual_is_anonymous" value="1" @checked(old('is_anonymous')) class="h-4 w-4 rounded border-line-strong text-brand focus:ring-brand">
                            <span>Tampilkan sebagai <strong>Anonim / Hamba Allah</strong></span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-muted">No. WhatsApp / HP (opsional)</label>
                        <input
                            type="text"
                            name="donor_phone"
                            value="{{ old('donor_phone') }}"
                            placeholder="08123456789"
                            class="w-full rounded-lg border border-line-strong bg-canvas px-3 py-2 text-sm text-ink outline-none focus:border-brand focus:bg-surface"
                        >
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-muted">Email donatur (opsional)</label>
                        <input
                            type="email"
                            name="donor_email"
                            value="{{ old('donor_email') }}"
                            placeholder="donatur@email.com"
                            class="w-full rounded-lg border border-line-strong bg-canvas px-3 py-2 text-sm text-ink outline-none focus:border-brand focus:bg-surface"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-ink">
                            Metode penerimaan <span class="text-danger">*</span>
                        </label>
                        <select name="payment_method" required class="w-full rounded-lg border border-line-strong bg-canvas px-3 py-2 text-sm text-ink outline-none focus:border-brand focus:bg-surface">
                            <option value="cash" @selected(old('payment_method', 'cash') === 'cash')>Tunai (Cash / Titipan)</option>
                            <option value="transfer" @selected(old('payment_method') === 'transfer')>Transfer Bank Langsung</option>
                            <option value="qris" @selected(old('payment_method') === 'qris')>QRIS Offline / Yayasan</option>
                            <option value="other" @selected(old('payment_method') === 'other')>Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-ink">Tanggal diterima</label>
                        <input
                            type="date"
                            name="donated_at"
                            value="{{ old('donated_at', now()->format('Y-m-d')) }}"
                            class="w-full rounded-lg border border-line-strong bg-canvas px-3 py-2 text-sm text-ink outline-none focus:border-brand focus:bg-surface"
                        >
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-medium text-muted">Doa / pesan kebaikan (opsional)</label>
                    <textarea
                        name="prayer_message"
                        rows="2"
                        placeholder="Tuliskan doa atau pesan donatur bila ada..."
                        class="w-full rounded-lg border border-line-strong bg-canvas px-3 py-2 text-sm text-ink outline-none focus:border-brand focus:bg-surface"
                    >{{ old('prayer_message') }}</textarea>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-medium text-muted">Catatan internal admin (opsional)</label>
                    <textarea
                        name="admin_note"
                        rows="2"
                        placeholder="Contoh: Diterima tunai di kantor oleh bendahara"
                        class="w-full rounded-lg border border-line-strong bg-canvas px-3 py-2 text-sm text-ink outline-none focus:border-brand focus:bg-surface"
                    >{{ old('admin_note') }}</textarea>
                </div>

                <div class="rounded-lg border border-line bg-canvas p-3.5">
                    <label class="inline-flex cursor-pointer items-start gap-2.5 text-xs text-ink-soft select-none">
                        <input type="checkbox" name="show_on_public" value="1" @checked(old('show_on_public', true)) class="mt-0.5 h-4 w-4 rounded border-line-strong text-brand focus:ring-brand">
                        <div>
                            <span class="font-semibold text-ink">Tampilkan di situs publik</span>
                            <p class="mt-0.5 text-muted">Nama (atau Anonim) dan nominal akan muncul di daftar donatur program terkait.</p>
                        </div>
                    </label>
                </div>

                <div class="flex flex-wrap justify-end gap-2.5 border-t border-line pt-4">
                    <button type="button" id="cancel-manual-donation-modal-btn" class="rounded-lg border border-line-strong px-4 py-2 text-xs font-medium text-ink-soft hover:bg-surface-subtle">
                        Batal
                    </button>
                    <button type="submit" class="rounded-lg bg-brand px-5 py-2 text-xs font-semibold text-white hover:bg-brand-deep">
                        Simpan donasi manual
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Executive Financial Brief Slide-Over Workspace (Anti-AI-Slop Redesign) --}}
    <div
        id="ai-analyst-modal"
        class="fixed inset-0 z-50 hidden justify-end bg-ink/45 backdrop-blur-[1px]"
        aria-hidden="true"
    >
        <div class="flex h-full w-full max-w-4xl flex-col border-l border-line bg-canvas shadow-xl">
            {{-- Quiet Editorial Header --}}
            <header class="flex shrink-0 items-start justify-between gap-4 border-b border-line bg-surface px-5 py-4 sm:px-7">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="font-display text-2xl font-semibold leading-none text-ink">Analis Laporan Keuangan</h3>
                        <span class="rounded border border-line bg-canvas px-2 py-0.5 font-mono text-[11px] text-muted">Executive Brief</span>
                    </div>
                    <p class="mt-1.5 text-xs text-muted">
                        Cakupan aktif:
                        <strong class="text-ink">{{ !empty($filters['status']) ? strtoupper($filters['status']) : 'Semua status' }}</strong>
                        &middot;
                        <strong class="text-ink">{{ !empty($filters['start_date']) || !empty($filters['end_date']) ? (($filters['start_date'] ?? 'Awal') . ' s/d ' . ($filters['end_date'] ?? 'Sekarang')) : '180 hari terakhir' }}</strong>
                        @if(!empty($filters['q']))
                            &middot; Cari <strong class="text-ink">"{{ $filters['q'] }}"</strong>
                        @endif
                    </p>
                </div>

                <button
                    type="button"
                    id="close-ai-analyst-modal-btn"
                    class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-line bg-canvas text-muted transition-colors hover:border-line-strong hover:text-ink"
                    title="Tutup panel"
                    aria-label="Tutup panel analis"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </header>

            {{-- Scrollable Brief Workspace --}}
            <div id="ai-right-panel" class="flex-1 overflow-y-auto px-5 py-5 sm:px-7 sm:py-6 space-y-5">
                {{-- Prompt & 1-Click Quick Topics --}}
                <div class="rounded-xl border border-line bg-surface p-4 sm:p-5 space-y-3.5">
                    <div class="flex flex-wrap items-center gap-1.5">
                        <span class="mr-1 text-xs text-muted">Topik cepat:</span>
                        <button
                            type="button"
                            data-ai-quick-prompt="Berikan Ringkasan Eksekutif arus kas donasi saat ini, tren bulanan, kontribusi pola Jumat Berkah serta periode gajian (tanggal 25-5), dan rekomendasi peningkatan penghimpunan bulan depan."
                            class="rounded-md border border-line bg-canvas px-3 py-1.5 text-xs font-medium text-ink transition-colors hover:border-brand hover:bg-brand-soft hover:text-brand"
                        >
                            Arus kas &amp; tren bulanan
                        </button>
                        <button
                            type="button"
                            data-ai-quick-prompt="Identifikasi program kampanye yang berstatus kritis (sisa hari sedikit namun capaian masih rendah) dibandingkan kampanye yang hampir/sudah 100%, lalu berikan strategi konkret untuk menyelamatkan target kampanye kritis tersebut."
                            class="rounded-md border border-line bg-canvas px-3 py-1.5 text-xs font-medium text-ink transition-colors hover:border-brand hover:bg-brand-soft hover:text-brand"
                        >
                            Kampanye kritis
                        </button>
                        <button
                            type="button"
                            data-ai-quick-prompt="Analisis rasio konversi pembayaran (paid vs pending, expired, failed) di setiap kanal pembayaran (QRIS, Transfer Bank Manual, Virtual Account, E-Wallet, Kasir Manual). Mengapa ada transaksi expired/pending dan bagaimana cara menekannya?"
                            class="rounded-md border border-line bg-canvas px-3 py-1.5 text-xs font-medium text-ink transition-colors hover:border-brand hover:bg-brand-soft hover:text-brand"
                        >
                            Konversi kanal bayar
                        </button>
                        <button
                            type="button"
                            data-ai-quick-prompt="Analisis struktur segmentasi donatur kita (proporsi Donatur VIP >= Rp 5 juta, tingkat retensi Repeat Donor, rata-rata nominal per transaksi, dan kebiasaan donatur anonim). Berikan program retensi terbaik untuk menjaga loyalitas mereka."
                            class="rounded-md border border-line bg-canvas px-3 py-1.5 text-xs font-medium text-ink transition-colors hover:border-brand hover:bg-brand-soft hover:text-brand"
                        >
                            Retensi donatur VIP
                        </button>
                    </div>

                    <form id="ai-analyst-form" class="flex flex-col gap-2.5 sm:flex-row sm:items-end">
                        <div class="flex-1">
                            <label for="ai-question-input" class="sr-only">Pertanyaan analisis keuangan</label>
                            <textarea
                                id="ai-question-input"
                                name="question"
                                rows="3"
                                required
                                placeholder="Tulis pertanyaan spesifik mengenai arus kas, kampanye, atau perilaku donatur..."
                                class="w-full resize-none rounded-lg border border-line-strong bg-canvas px-3.5 py-2.5 text-xs leading-relaxed text-ink outline-none transition-colors focus:border-brand focus:bg-surface sm:text-sm"
                            >Berikan Ringkasan Eksekutif arus kas donasi saat ini, tren bulanan, kontribusi pola Jumat Berkah serta periode gajian (tanggal 25-5), dan rekomendasi peningkatan penghimpunan bulan depan.</textarea>
                        </div>
                        <button
                            type="submit"
                            id="ai-submit-btn"
                            class="inline-flex shrink-0 items-center justify-center rounded-lg bg-brand px-5 py-2.5 text-xs font-semibold text-white transition-colors hover:bg-brand-deep disabled:opacity-50 sm:h-[42px] sm:text-sm"
                        >
                            <span id="ai-submit-btn-text">Buat ringkasan</span>
                        </button>
                    </form>
                </div>

                {{-- Empty State --}}
                <div id="ai-empty-state" class="rounded-xl border border-dashed border-line-strong bg-surface px-6 py-12 text-center">
                    <p class="font-display text-xl font-semibold text-ink">Siap menyusun memo keuangan</p>
                    <p class="mx-auto mt-2 max-w-md text-xs leading-6 text-muted sm:text-sm">
                        Klik salah satu topik cepat di atas untuk langsung menyusun laporan, atau tulis pertanyaan spesifik lalu tekan <strong>Buat ringkasan</strong>.
                    </p>
                </div>

                {{-- Loading State --}}
                <div id="ai-loading-state" class="hidden rounded-xl border border-line bg-surface px-6 py-12 text-center">
                    <div class="mx-auto h-7 w-7 animate-spin rounded-full border-2 border-line-strong border-t-brand"></div>
                    <p class="mt-4 text-sm font-semibold text-ink">Menghitung agregat dan menyusun laporan...</p>
                    <p class="mt-1 text-xs text-muted">Data ditarik langsung dari transaksi terfilter di database Laravel.</p>
                </div>

                {{-- Error State --}}
                <div id="ai-error-state" class="hidden rounded-xl border border-danger/30 bg-danger-soft px-5 py-4 text-xs text-danger sm:text-sm">
                    <p class="font-semibold">Analisis belum dapat diproses</p>
                    <p id="ai-error-message" class="mt-1 leading-relaxed break-words"></p>
                </div>

                {{-- Document-Style Brief Output --}}
                <article id="ai-result-container" class="hidden overflow-hidden rounded-xl border border-line bg-surface">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-line px-5 py-4 sm:px-6">
                        <div>
                            <h4 class="font-display text-xl font-semibold text-ink">Memo Ringkasan Keuangan</h4>
                            <p class="mt-0.5 text-xs text-muted">
                                Dihitung dari agregat database transaksi yang sedang difilter
                            </p>
                        </div>
                        <button
                            type="button"
                            id="ai-copy-btn"
                            class="rounded-md border border-line-strong bg-canvas px-3 py-1.5 text-xs font-medium text-ink transition-colors hover:bg-surface-subtle"
                        >
                            Salin memo
                        </button>
                    </div>

                    {{-- Hairline KPI Strip --}}
                    <div id="ai-kpi-strip" class="hidden grid grid-cols-2 divide-y divide-line border-b border-line bg-canvas sm:grid-cols-4 sm:divide-x sm:divide-y-0 tabular-nums">
                        <div class="px-5 py-3.5">
                            <p class="text-[11px] text-muted">Kas masuk bersih</p>
                            <p id="ai-kpi-cash" class="mt-1 text-sm font-bold text-brand sm:text-base">-</p>
                        </div>
                        <div class="px-5 py-3.5">
                            <p class="text-[11px] text-muted">Transaksi lunas</p>
                            <p id="ai-kpi-tx" class="mt-1 text-sm font-bold text-ink sm:text-base">-</p>
                        </div>
                        <div class="px-5 py-3.5">
                            <p class="text-[11px] text-muted">Rasio konversi</p>
                            <p id="ai-kpi-conv" class="mt-1 text-sm font-bold text-ink sm:text-base">-</p>
                        </div>
                        <div class="px-5 py-3.5">
                            <p class="text-[11px] text-muted">Kampanye kritis</p>
                            <p id="ai-kpi-crit" class="mt-1 text-sm font-bold text-danger sm:text-base">-</p>
                        </div>
                    </div>

                    {{-- Brief Markdown Body --}}
                    <div id="ai-result-content" class="space-y-4 px-5 py-5 text-xs leading-7 text-ink-soft sm:px-6 sm:py-6 sm:text-sm"></div>

                    {{-- Discreet Footer Note --}}
                    <div class="flex flex-wrap items-center justify-between gap-2 border-t border-line bg-canvas px-5 py-2.5 text-[11px] text-muted sm:px-6">
                        <span>Basis data: agregat transaksi terverifikasi</span>
                        <span id="ai-model-badge" class="font-mono text-[10px] text-muted/80"></span>
                    </div>
                </article>
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

        if (modal) {
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
                if (e.target === modal) closeModal();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
            });

            anonCheckbox?.addEventListener('change', function () {
                if (anonCheckbox.checked && (!donorNameInput.value || donorNameInput.value.trim() === '')) {
                    donorNameInput.value = 'Hamba Allah';
                }
            });

            @if ($errors->any() && (old('campaign_id') || old('amount')))
                openModal();
            @endif
        }

        // === Executive Financial Brief Workspace ===
        const aiModal = document.getElementById('ai-analyst-modal');
        const openAiBtn = document.getElementById('open-ai-analyst-modal-btn');
        const closeAiBtn = document.getElementById('close-ai-analyst-modal-btn');
        const aiForm = document.getElementById('ai-analyst-form');
        const aiQuestionInput = document.getElementById('ai-question-input');
        const aiSubmitBtn = document.getElementById('ai-submit-btn');
        const aiSubmitBtnText = document.getElementById('ai-submit-btn-text');
        const aiLoadingState = document.getElementById('ai-loading-state');
        const aiEmptyState = document.getElementById('ai-empty-state');
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
            if (e.target === aiModal) closeAiModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && aiModal && !aiModal.classList.contains('hidden')) closeAiModal();
        });

        // 1-Click Quick Topic Chips (populates & immediately runs brief)
        const quickPromptBtns = document.querySelectorAll('[data-ai-quick-prompt]');
        quickPromptBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                quickPromptBtns.forEach((b) => {
                    b.classList.remove('bg-brand', 'text-white', 'border-brand');
                    b.classList.add('bg-canvas', 'text-ink', 'border-line');
                });
                btn.classList.remove('bg-canvas', 'text-ink', 'border-line');
                btn.classList.add('bg-brand', 'text-white', 'border-brand');

                const promptText = btn.getAttribute('data-ai-quick-prompt') || '';
                if (aiQuestionInput) {
                    aiQuestionInput.value = promptText;
                }
                aiForm?.requestSubmit();
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

        function cleanAiArtifacts(text) {
            return String(text || '')
                .replace(/^[\u{1F300}-\u{1FAFF}\u{2600}-\u{27BF}\s]+/gu, '')
                .replace(/\*\*\[([^\]]+)\]:?\*\*/g, '**$1:**')
                .replace(/^\[([^\]]+)\]:/g, '**$1:**');
        }

        function formatStatusPill(cellText) {
            const raw = String(cellText || '').trim();
            const lower = raw.toLowerCase();
            if (/mendesak|kritis|rendah|gagal|bocor/.test(lower)) {
                return '<span class="inline-flex rounded-md bg-danger-soft px-2 py-0.5 text-[11px] font-semibold text-danger">' + escapeHtml(raw) + '</span>';
            }
            if (/perhatian|waspada|sedang|pending|menunggu/.test(lower)) {
                return '<span class="inline-flex rounded-md bg-ochre-soft px-2 py-0.5 text-[11px] font-semibold text-ochre-ink">' + escapeHtml(raw) + '</span>';
            }
            if (/aman|efektif|dominan|optimal|tercapai|kuat|stabil|baik/.test(lower)) {
                return '<span class="inline-flex rounded-md bg-brand-soft px-2 py-0.5 text-[11px] font-semibold text-brand">' + escapeHtml(raw) + '</span>';
            }
            return formatInlineMarkdown(raw);
        }

        function formatInlineMarkdown(text) {
            let safe = escapeHtml(cleanAiArtifacts(text));
            safe = safe.replace(/\*\*(.+?)\*\*/g, '<strong class="font-semibold text-ink">$1</strong>');
            safe = safe.replace(/\*(.+?)\*/g, '<em>$1</em>');
            safe = safe.replace(/`([^`]+)`/g, '<code class="rounded bg-surface-subtle px-1.5 py-0.5 font-mono text-xs text-ink">$1</code>');
            return safe;
        }

        function parseTableCells(rowLine) {
            return rowLine
                .trim()
                .replace(/^\|/, '')
                .replace(/\|$/, '')
                .split('|')
                .map((cell) => cell.trim());
        }

        function isTableSeparator(line) {
            return /^\|?(\s*:?-+:?\s*\|)+\s*:?-+:?\s*\|?$/.test(line.trim());
        }

        function renderMarkdownToHtml(markdown) {
            const lines = String(markdown || '').split(/\r?\n/);
            let html = '';
            let listType = null;
            let olCounter = 0;

            function closeList() {
                if (listType) {
                    html += listType === 'ol' ? '</ol>' : '</ul>';
                    listType = null;
                    olCounter = 0;
                }
            }

            for (let i = 0; i < lines.length; i++) {
                const line = lines[i].trim();

                if (!line) {
                    closeList();
                    continue;
                }

                if (line.includes('|') && i + 1 < lines.length && isTableSeparator(lines[i + 1])) {
                    closeList();
                    const headers = parseTableCells(line);
                    const statusColIdx = headers.findIndex((h) => /status/i.test(h));
                    i += 2;
                    html += '<div class="my-3 overflow-x-auto rounded-lg border border-line"><table class="min-w-full border-collapse text-left text-xs tabular-nums">';
                    html += '<thead class="border-b border-line bg-canvas text-muted"><tr>';
                    headers.forEach((h, idx) => {
                        const align = idx === statusColIdx ? 'text-right' : '';
                        html += '<th class="whitespace-nowrap px-4 py-2.5 font-medium ' + align + '">' + formatInlineMarkdown(h) + '</th>';
                    });
                    html += '</tr></thead><tbody class="divide-y divide-line bg-surface">';

                    while (i < lines.length && lines[i].trim().includes('|') && !isTableSeparator(lines[i])) {
                        const cells = parseTableCells(lines[i]);
                        html += '<tr class="hover:bg-canvas/60">';
                        for (let c = 0; c < headers.length; c++) {
                            const val = cells[c] || '';
                            if (c === statusColIdx) {
                                html += '<td class="px-4 py-2.5 text-right align-middle whitespace-nowrap">' + formatStatusPill(val) + '</td>';
                            } else if (c === 0) {
                                html += '<td class="px-4 py-2.5 align-top font-medium text-ink">' + formatInlineMarkdown(val) + '</td>';
                            } else {
                                html += '<td class="px-4 py-2.5 align-top text-ink-soft">' + formatInlineMarkdown(val) + '</td>';
                            }
                        }
                        html += '</tr>';
                        i++;
                    }
                    i--;
                    html += '</tbody></table></div>';
                    continue;
                }

                if (line.startsWith('> ')) {
                    closeList();
                    const quoteLines = [line.slice(2)];
                    while (i + 1 < lines.length && lines[i + 1].trim().startsWith('> ')) {
                        i++;
                        quoteLines.push(lines[i].trim().slice(2));
                    }
                    html += '<div class="my-2 rounded-r-lg border-l-[3px] border-brand bg-canvas px-4 py-3.5 text-xs leading-relaxed text-ink sm:text-sm">'
                        + quoteLines.map((q) => formatInlineMarkdown(q)).join('<br>')
                        + '</div>';
                    continue;
                }

                if (/^---+$/.test(line)) {
                    closeList();
                    html += '<hr class="my-4 border-line">';
                    continue;
                }

                if (line.startsWith('### ')) {
                    closeList();
                    html += '<h4 class="mt-5 mb-2 border-b border-line pb-1.5 font-display text-base font-semibold text-ink sm:text-lg">' + formatInlineMarkdown(line.slice(4)) + '</h4>';
                } else if (line.startsWith('## ')) {
                    closeList();
                    html += '<h3 class="mt-5 mb-2 border-b border-line pb-1.5 font-display text-base font-semibold text-ink sm:text-lg">' + formatInlineMarkdown(line.slice(3)) + '</h3>';
                } else if (line.startsWith('# ')) {
                    closeList();
                    html += '<h2 class="mt-5 mb-2 font-display text-xl font-semibold text-ink">' + formatInlineMarkdown(line.slice(2)) + '</h2>';
                } else if (/^(\*|-)\s+/.test(line)) {
                    if (listType !== 'ul') {
                        closeList();
                        html += '<ul class="my-2 divide-y divide-line rounded-lg border border-line bg-canvas/60">';
                        listType = 'ul';
                    }
                    html += '<li class="px-4 py-2.5 text-xs leading-relaxed text-ink-soft sm:text-sm">' + formatInlineMarkdown(line.replace(/^(\*|-)\s+/, '')) + '</li>';
                } else if (/^\d+\.\s+/.test(line)) {
                    if (listType !== 'ol') {
                        closeList();
                        html += '<ol class="my-2 space-y-2">';
                        listType = 'ol';
                        olCounter = 0;
                    }
                    olCounter++;
                    const num = String(olCounter).padStart(2, '0');
                    html += '<li class="flex items-start gap-3 rounded-lg border border-line bg-surface px-4 py-3 text-xs leading-relaxed text-ink-soft sm:text-sm">'
                        + '<span class="mt-0.5 inline-flex h-5 w-6 shrink-0 items-center justify-center rounded bg-brand-soft font-mono text-[11px] font-semibold text-brand">' + num + '</span>'
                        + '<span class="min-w-0 flex-1">' + formatInlineMarkdown(line.replace(/^\d+\.\s+/, '')) + '</span>'
                        + '</li>';
                } else {
                    closeList();
                    html += '<p class="leading-relaxed text-ink-soft">' + formatInlineMarkdown(line) + '</p>';
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
            if (aiSubmitBtnText) aiSubmitBtnText.textContent = 'Menyusun...';

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
                    throw new Error(data.message || 'Gagal memperoleh respons dari layanan analis.');
                }

                lastRawMarkdown = data.ai_response || '';
                if (aiModelBadge) {
                    aiModelBadge.textContent = data.model_used || 'Gemini';
                }

                const kpiStrip = document.getElementById('ai-kpi-strip');
                if (data.context_summary && kpiStrip) {
                    const fmtRp = (val) => 'Rp ' + Number(val || 0).toLocaleString('id-ID');
                    document.getElementById('ai-kpi-cash').textContent = fmtRp(data.context_summary.filtered_paid_cash_in);
                    document.getElementById('ai-kpi-tx').textContent = `${data.context_summary.filtered_paid_tx || 0} / ${data.context_summary.filtered_total_rows || 0}`;
                    document.getElementById('ai-kpi-conv').textContent = `${data.context_summary.conversion_rate_pct || 0}%`;
                    document.getElementById('ai-kpi-crit').textContent = `${data.context_summary.critical_campaigns_count || 0} program`;
                    kpiStrip.classList.remove('hidden');
                }

                if (aiResultContent) {
                    aiResultContent.innerHTML = renderMarkdownToHtml(lastRawMarkdown);
                }
                aiResultContainer?.classList.remove('hidden');
            } catch (err) {
                if (aiErrorMessage) {
                    aiErrorMessage.textContent = err.message || 'Terjadi kesalahan jaringan saat menghubungi server analis.';
                }
                aiErrorState?.classList.remove('hidden');
            } finally {
                aiLoadingState?.classList.add('hidden');
                if (aiSubmitBtn) aiSubmitBtn.disabled = false;
                if (aiSubmitBtnText) aiSubmitBtnText.textContent = 'Buat ringkasan';
            }
        });

        aiCopyBtn?.addEventListener('click', function () {
            if (!lastRawMarkdown) return;
            navigator.clipboard.writeText(lastRawMarkdown).then(function () {
                const originalText = aiCopyBtn.textContent;
                aiCopyBtn.textContent = 'Tersalin';
                setTimeout(function () {
                    aiCopyBtn.textContent = originalText;
                }, 1800);
            });
        });

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('open_ai') === '1') {
            openAiModal();
        }
    });
</script>
@endpush
