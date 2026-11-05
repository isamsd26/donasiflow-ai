<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\PaymentTransaction;
use App\Models\Setting;
use App\Support\PublicSite;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use App\Services\MidtransService;

class PublicDonationController extends Controller
{
    public function create(string $slug): View
    {
        $campaign = $this->activeCampaign($slug);

        return view('pages.donation.create', [
            'site' => PublicSite::site("Donasi {$campaign->title}", $campaign->summary),
            'navigation' => PublicSite::navigation(),
            'socialLinks' => PublicSite::socials(),
            'campaign' => $campaign,
        ]);
    }

    public function store(Request $request, string $slug, MidtransService $midtransService): RedirectResponse
    {
        $campaign = $this->activeCampaign($slug);

        $validated = $request->validate([
            'amount' => ['required', 'string'],
            'donor_name' => ['required', 'string', 'max:255'],
            'donor_email' => ['nullable', 'email', 'max:255'],
            'donor_phone' => ['required', 'string', 'max:50'],
            'prayer_message' => ['nullable', 'string', 'max:1000'],
            'is_anonymous' => ['nullable', 'boolean'],
            'payment_gateway' => ['required', 'in:manual'],
        ]);

        $baseAmount = (int) preg_replace('/[^\d]/', '', (string) $validated['amount']);

        if ($baseAmount < 1000) {
            return back()->withErrors(['amount' => 'Nominal donasi minimal adalah Rp 1.000. Masukkan nominal yang valid.'])->withInput();
        }

        if ($baseAmount > 500000000) {
            return back()->withErrors(['amount' => 'Nominal donasi melebihi batas maksimal transaksi (Rp 500.000.000).'])->withInput();
        }

        $cleanPhone = preg_replace('/[^\d]/', '', (string) $validated['donor_phone']);
        if (strlen($cleanPhone) < 8 || strlen($cleanPhone) > 16) {
            return back()->withErrors(['donor_phone' => 'Nomor WhatsApp / HP tidak valid (harus terdiri dari 8 hingga 16 digit angka).'])->withInput();
        }

        $cleanPrayerMessage = filled($validated['prayer_message'] ?? null)
            ? trim(strip_tags((string) $validated['prayer_message']))
            : null;

        $isMidtrans = $validated['payment_gateway'] === 'midtrans';

        $reference = $this->generateReference();
        $uniqueCode = $isMidtrans ? 0 : $this->generateUniqueCode($baseAmount);
        $transferAmount = $baseAmount + $uniqueCode;
        $bankAccount = $this->bankAccount();

        $donation = DB::transaction(function () use ($validated, $campaign, $baseAmount, $uniqueCode, $transferAmount, $reference, $bankAccount, $isMidtrans, $cleanPrayerMessage) {
            $donor = $this->findOrCreateDonor($validated);
            $donationData = [
                'campaign_id' => $campaign->id,
                'donor_id' => $donor?->id,
                'reference' => $reference,
                'donor_name' => trim($validated['donor_name']),
                'donor_email' => $validated['donor_email'] ?? null,
                'donor_phone' => $validated['donor_phone'],
                'amount' => $transferAmount,
                'prayer_message' => $cleanPrayerMessage,
                'is_anonymous' => (bool) ($validated['is_anonymous'] ?? false),
                'show_on_public' => true,
                'status' => 'pending',
                'expires_at' => now()->addDay(),
                'metadata' => ['source' => $isMidtrans ? 'public-midtrans-flow' : 'public-bank-transfer-flow'],
            ];

            if (Schema::hasColumn('donations', 'base_amount')) {
                $donationData['base_amount'] = $baseAmount;
            }

            if (Schema::hasColumn('donations', 'unique_code')) {
                $donationData['unique_code'] = $uniqueCode;
            }

            if (!$isMidtrans) {
                if (Schema::hasColumn('donations', 'payment_bank')) {
                    $donationData['payment_bank'] = $bankAccount['bank'];
                }

                if (Schema::hasColumn('donations', 'payment_account_number')) {
                    $donationData['payment_account_number'] = $bankAccount['number'];
                }

                if (Schema::hasColumn('donations', 'payment_account_name')) {
                    $donationData['payment_account_name'] = $bankAccount['name'];
                }
            }

            $donation = Donation::query()->create($donationData);

            $paymentPayload = [];
            if (!$isMidtrans) {
                $paymentPayload = [
                    'message' => 'Manual bank transfer flow',
                    'base_amount' => $baseAmount,
                    'unique_code' => $uniqueCode,
                    'bank_account' => $bankAccount,
                ];
            } else {
                $paymentPayload = [
                    'message' => 'Midtrans checkout flow',
                    'base_amount' => $baseAmount,
                ];
            }

            PaymentTransaction::query()->create([
                'donation_id' => $donation->id,
                'gateway' => $isMidtrans ? 'midtrans' : 'bank_transfer',
                'external_id' => $isMidtrans ? $reference : null,
                'reference' => $reference,
                'payment_method' => $isMidtrans ? null : 'manual_bank_transfer',
                'gross_amount' => $transferAmount,
                'fee_amount' => 0,
                'net_amount' => $transferAmount,
                'status' => 'pending',
                'expires_at' => now()->addDay(),
                'payload' => $paymentPayload,
            ]);

            return $donation;
        });

        $snapToken = null;
        if ($isMidtrans) {
            $transactionDetails = [
                'order_id' => $donation->reference,
                'gross_amount' => $transferAmount,
            ];
            $customerDetails = [
                'first_name' => $donation->donor_name,
                'email' => $donation->donor_email ?? 'guest@example.com',
                'phone' => $donation->donor_phone,
            ];
            $snapToken = $midtransService->getSnapToken($transactionDetails, $customerDetails);
            
            // Save snap token to transaction payload
            $transaction = $donation->paymentTransactions()->first();
            if ($transaction) {
                $payload = is_array($transaction->payload) ? $transaction->payload : [];
                $payload['snap_token'] = $snapToken;
                $transaction->payload = $payload;
                $transaction->save();
            }
        }

        if ($donation && $donation->donor_phone && !$isMidtrans) {
            try {
                \App\Support\MetaWaService::sendDonationPendingTemplate($donation);
                \App\Support\MetaWaService::sendAdminDonationNotification($donation);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Gagal kirim notifikasi WhatsApp donasi: ' . $e->getMessage());
            }
        }

        return redirect()->route('donation.show', $donation->reference)
            ->with('success', 'Donasi Anda sudah tercatat. Silakan selesaikan pembayaran.');
    }

    public function show(string $reference): View
    {
        $donation = Donation::query()
            ->with(['campaign', 'paymentTransactions'])
            ->where('reference', $reference)
            ->firstOrFail();

        return view('pages.donation.show', [
            'site' => PublicSite::site("Status {$donation->reference}", 'Status donasi transfer rekening manual.'),
            'navigation' => PublicSite::navigation(),
            'socialLinks' => PublicSite::socials(),
            'donation' => $donation,
            'transaction' => $donation->paymentTransactions()->latest()->first(),
            'whatsAppUrl' => $this->whatsAppConfirmationUrl($donation),
        ]);
    }

    public function invoice(string $reference)
    {
        $donation = Donation::query()
            ->with(['campaign', 'paymentTransactions'])
            ->where('reference', $reference)
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.invoice', ['donation' => $donation]);
        
        return $pdf->download("Invoice-{$donation->reference}.pdf");
    }

    public function lookup(Request $request): View|RedirectResponse
    {
        $reference = strtoupper(trim((string) $request->string('reference')));

        if ($reference === '') {
            return view('pages.donation.lookup', [
                'site' => PublicSite::site('Cek Status Donasi', 'Cari status donasi menggunakan nomor referensi tanpa perlu login.'),
                'navigation' => PublicSite::navigation(),
                'socialLinks' => PublicSite::socials(),
                'reference' => '',
                'result' => null,
            ]);
        }

        $donation = Donation::query()
            ->with('campaign')
            ->where('reference', $reference)
            ->first();

        return view('pages.donation.lookup', [
            'site' => PublicSite::site('Cek Status Donasi', 'Cari status donasi menggunakan nomor referensi tanpa perlu login.'),
            'navigation' => PublicSite::navigation(),
            'socialLinks' => PublicSite::socials(),
            'reference' => $reference,
            'result' => $donation,
        ]);
    }

    protected function activeCampaign(string $slug): Campaign
    {
        return Campaign::query()
            ->where('slug', $slug)
            ->where('publish_status', 'published')
            ->where('public_status', 'active')
            ->firstOrFail();
    }

    protected function findOrCreateDonor(array $validated): ?Donor
    {
        if (! empty($validated['donor_email'])) {
            return Donor::query()->firstOrCreate(
                ['email' => $validated['donor_email']],
                [
                    'name' => $validated['donor_name'],
                    'phone' => $validated['donor_phone'],
                ]
            );
        }

        return Donor::query()->firstOrCreate(
            ['phone' => $validated['donor_phone']],
            [
                'name' => $validated['donor_name'],
                'email' => null,
            ]
        );
    }

    protected function generateReference(): string
    {
        do {
            $reference = 'INV-'.now()->format('Ymd').'-'.str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Donation::query()->where('reference', $reference)->exists());

        return $reference;
    }

    protected function generateUniqueCode(int $baseAmount): int
    {
        for ($attempt = 0; $attempt < 40; $attempt++) {
            $code = random_int(1, 999);

            $exists = Donation::query()
                ->where('status', 'pending')
                ->where('expires_at', '>', now());

            if (Schema::hasColumn('donations', 'amount')) {
                $exists->where('amount', $baseAmount + $code);
            }

            $exists = $exists->exists();

            if (! $exists) {
                return $code;
            }
        }

        return random_int(1, 999);
    }

    protected function bankAccount(): array
    {
        return [
            'bank' => $this->settingValue('payment_bank', 'Bank Syariah Nasional'),
            'number' => $this->settingValue('payment_account_number', '8899001122'),
            'name' => $this->settingValue('payment_account_name', 'Yayasan DonasiFlow Nusantara'),
        ];
    }

    protected function whatsAppConfirmationUrl(Donation $donation): ?string
    {
        $phone = preg_replace('/[^\d]/', '', (string) $this->settingValue('whatsapp', ''));

        if ($phone === '') {
            return null;
        }

        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        $message = implode("\n", [
            'Halo admin, saya sudah transfer donasi.',
            'Referensi: '.$donation->reference,
            'Campaign: '.($donation->campaign?->title ?? '-'),
            'Total transfer: Rp '.number_format((float) $donation->amount, 0, ',', '.'),
            'Mohon dibantu cek dan verifikasi. Terima kasih.',
        ]);

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($message);
    }

    protected function settingValue(string $key, ?string $default = null): ?string
    {
        $setting = Setting::query()->where('key', $key)->first();

        return $setting?->value['value'] ?? $default;
    }
}
