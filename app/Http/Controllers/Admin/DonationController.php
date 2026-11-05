<?php

namespace App\Http\Controllers\Admin;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\PaymentTransaction;
use App\Support\CampaignMetrics;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use App\Exports\DonationsExport;
use Maatwebsite\Excel\Facades\Excel;

class DonationController extends AdminController
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::in(['pending', 'paid', 'failed', 'expired', 'refunded'])],
            'q' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $query = Donation::query()
            ->with('campaign')
            ->latest();

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        if (! empty($filters['q'])) {
            $keyword = trim((string) $filters['q']);
            $digits = preg_replace('/[^\d]/', '', $keyword);

            $query->where(function ($query) use ($keyword, $digits): void {
                $query
                    ->where('reference', 'like', "%{$keyword}%")
                    ->orWhere('donor_name', 'like', "%{$keyword}%")
                    ->orWhere('donor_email', 'like', "%{$keyword}%")
                    ->orWhere('donor_phone', 'like', "%{$keyword}%")
                    ->orWhereHas('campaign', fn ($campaignQuery) => $campaignQuery->where('title', 'like', "%{$keyword}%"));

                if ($digits !== '') {
                    $query->orWhere('amount', (int) $digits);
                }
            });
        }

        return view('admin.donations.index', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('donations'),
            'filters' => $filters,
            'donations' => $query->paginate(12)->withQueryString(),
            'campaigns' => Campaign::query()->where('publish_status', 'published')->orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function storeManual(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'campaign_id' => ['required', 'exists:campaigns,id'],
            'amount' => ['required', 'string'],
            'donor_name' => ['required', 'string', 'max:255'],
            'donor_phone' => ['nullable', 'string', 'max:50'],
            'donor_email' => ['nullable', 'email', 'max:255'],
            'donated_at' => ['nullable', 'date'],
            'payment_method' => ['required', Rule::in(['cash', 'transfer', 'qris', 'other'])],
            'prayer_message' => ['nullable', 'string', 'max:1000'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
            'is_anonymous' => ['nullable', 'boolean'],
            'show_on_public' => ['nullable', 'boolean'],
        ]);

        $cleanAmount = (int) preg_replace('/[^\d]/', '', (string) $validated['amount']);

        if ($cleanAmount <= 0) {
            return back()->withErrors(['amount' => 'Nominal donasi harus lebih besar dari Rp 0.'])->withInput();
        }

        $campaign = Campaign::findOrFail($validated['campaign_id']);

        $donatedAt = ! empty($validated['donated_at'])
            ? Carbon::parse($validated['donated_at'])->setTimeFrom(now())
            : now();

        $paymentMethodLabels = [
            'cash' => 'Tunai (Cash)',
            'transfer' => 'Transfer Bank Langsung',
            'qris' => 'QRIS Offline',
            'other' => 'Lainnya',
        ];
        $methodLabel = $paymentMethodLabels[$validated['payment_method']] ?? 'Manual Admin';

        DB::transaction(function () use ($validated, $cleanAmount, $campaign, $donatedAt, $methodLabel) {
            $donor = null;
            if (! empty($validated['donor_email'])) {
                $donor = Donor::query()->firstOrCreate(
                    ['email' => $validated['donor_email']],
                    [
                        'name' => $validated['donor_name'],
                        'phone' => $validated['donor_phone'] ?? null,
                    ]
                );
            } elseif (! empty($validated['donor_phone'])) {
                $donor = Donor::query()->firstOrCreate(
                    ['phone' => $validated['donor_phone']],
                    [
                        'name' => $validated['donor_name'],
                        'email' => null,
                    ]
                );
            }

            do {
                $reference = 'MAN-' . now()->format('Ymd') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            } while (Donation::query()->where('reference', $reference)->exists());

            $isAnonymous = ! empty($validated['is_anonymous']);
            $showOnPublic = isset($validated['show_on_public']) ? (bool) $validated['show_on_public'] : true;

            $donationData = [
                'campaign_id' => $campaign->id,
                'donor_id' => $donor?->id,
                'reference' => $reference,
                'donor_name' => $validated['donor_name'],
                'donor_email' => $validated['donor_email'] ?? null,
                'donor_phone' => $validated['donor_phone'] ?? null,
                'amount' => $cleanAmount,
                'prayer_message' => $validated['prayer_message'] ?? null,
                'is_anonymous' => $isAnonymous,
                'show_on_public' => $showOnPublic,
                'status' => 'paid',
                'paid_at' => $donatedAt,
                'verified_at' => now(),
                'verified_by' => Auth::id(),
                'admin_note' => $validated['admin_note'] ?? null,
                'metadata' => [
                    'source' => 'admin_manual',
                    'payment_method' => $validated['payment_method'],
                    'payment_method_label' => $methodLabel,
                    'created_by_admin_id' => Auth::id(),
                    'created_by_admin_name' => Auth::user()?->name,
                ],
            ];

            if (Schema::hasColumn('donations', 'base_amount')) {
                $donationData['base_amount'] = $cleanAmount;
            }

            if (Schema::hasColumn('donations', 'unique_code')) {
                $donationData['unique_code'] = 0;
            }

            if (Schema::hasColumn('donations', 'payment_bank') && $validated['payment_method'] === 'transfer') {
                $donationData['payment_bank'] = 'Direct Transfer';
            }

            $donation = new Donation($donationData);
            $donation->created_at = $donatedAt;
            $donation->save();

            PaymentTransaction::query()->create([
                'donation_id' => $donation->id,
                'gateway' => 'manual_admin',
                'reference' => $reference,
                'payment_method' => $validated['payment_method'],
                'gross_amount' => $cleanAmount,
                'fee_amount' => 0,
                'net_amount' => $cleanAmount,
                'status' => 'paid',
                'paid_at' => $donatedAt,
                'payload' => [
                    'message' => 'Input manual oleh admin: ' . (Auth::user()?->name ?? 'Admin'),
                    'payment_method_label' => $methodLabel,
                ],
            ]);

            CampaignMetrics::sync($campaign);
        });

        // Catatan: Tidak mengirim notifikasi WhatsApp otomatis karena donasi diterima langsung secara manual oleh admin

        return redirect()->route('admin.donations')
            ->with('success', "Donasi manual untuk campaign \"{$campaign->title}\" sebesar Rp " . number_format($cleanAmount, 0, ',', '.') . " berhasil ditambahkan.");
    }

    public function show(Donation $donation): View
    {
        $donation->load(['campaign']);
        return view('admin.donations.show', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('donations'),
            'donation' => $donation,
        ]);
    }

    public function export(Request $request)
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::in(['pending', 'paid', 'failed', 'expired', 'refunded'])],
            'q' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $fileNameParts = ['Laporan-Donasi'];

        if (! empty($filters['status'])) {
            $fileNameParts[] = strtoupper($filters['status']);
        }

        if (! empty($filters['start_date']) && ! empty($filters['end_date'])) {
            $fileNameParts[] = $filters['start_date'] . '_sd_' . $filters['end_date'];
        } elseif (! empty($filters['start_date'])) {
            $fileNameParts[] = 'Mulai_' . $filters['start_date'];
        } elseif (! empty($filters['end_date'])) {
            $fileNameParts[] = 'Sampai_' . $filters['end_date'];
        } else {
            $fileNameParts[] = 'Semua-Periode_' . now()->format('Ymd-Hi');
        }

        $fileName = implode('_', $fileNameParts) . '.xlsx';

        return Excel::download(new DonationsExport($filters), $fileName);
    }

    public function updateStatus(Request $request, Donation $donation): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'paid', 'failed', 'expired', 'refunded'])],
            'admin_note' => ['nullable', 'string'],
        ]);

        $isPaid = $validated['status'] === 'paid';

        $update = [
            'status' => $validated['status'],
            'paid_at' => $isPaid ? now() : null,
        ];

        if (Schema::hasColumn('donations', 'verified_at')) {
            $update['verified_at'] = $isPaid ? now() : null;
        }

        if (Schema::hasColumn('donations', 'verified_by')) {
            $update['verified_by'] = $isPaid ? Auth::id() : null;
        }

        if (Schema::hasColumn('donations', 'admin_note')) {
            $update['admin_note'] = $validated['admin_note'] ?? null;
        }

        $donation->update($update);

        $donation->paymentTransactions()->latest()->first()?->update([
            'status' => $validated['status'],
            'paid_at' => $isPaid ? now() : null,
        ]);

        if ($donation->campaign) {
            CampaignMetrics::sync($donation->campaign);
        }

        // Kirim notifikasi WA ke donatur saat donasi dikonfirmasi sebagai paid
        if ($isPaid && $donation->donor_phone) {
            try {
                $donation->load('campaign');
                \App\Support\MetaWaService::sendDonationPaidTemplate($donation);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Gagal kirim notifikasi WA konfirmasi donasi: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.donations')->with('success', 'Status donasi berhasil diperbarui.');
    }
}
