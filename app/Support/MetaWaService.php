<?php

namespace App\Support;

use App\Models\Donation;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaWaService
{
    public static function isEnabled(): bool
    {
        if (filter_var(env('META_WA_ENABLED', false), FILTER_VALIDATE_BOOLEAN)) {
            return true;
        }

        $setting = Setting::query()->where('key', 'meta_wa_enabled')->first();
        $val = $setting?->value['value'] ?? '0';

        return in_array((string) $val, ['1', 'true'], true);
    }

    public static function getConfig(): array
    {
        return [
            'token' => env('META_WA_TOKEN') ?: (Setting::query()->where('key', 'meta_wa_token')->first()?->value['value'] ?? null),
            'phone_id' => env('META_WA_PHONE_NUMBER_ID') ?: (Setting::query()->where('key', 'meta_wa_phone_number_id')->first()?->value['value'] ?? null),
            'template_name' => env('META_WA_TEMPLATE_NAME') ?: (Setting::query()->where('key', 'meta_wa_template_name')->first()?->value['value'] ?? 'donasiflow_pending_notif'),
            'admin_template_name' => env('META_WA_ADMIN_TEMPLATE_NAME') ?: (Setting::query()->where('key', 'meta_wa_admin_template_name')->first()?->value['value'] ?? 'donasiflow_admin_alert'),
            'paid_template_name' => env('META_WA_PAID_TEMPLATE_NAME') ?: (Setting::query()->where('key', 'meta_wa_paid_template_name')->first()?->value['value'] ?? 'donasiflow_paid_receipt'),
            'admin_phone' => Setting::query()->where('key', 'whatsapp')->first()?->value['value'] ?? null,
        ];
    }

    public static function sendTemplate(string $phone, string $templateName, array $parameters = []): bool
    {
        if (!self::isEnabled()) {
            Log::info('[DEMO WA SIMULATION] Meta WA nonaktif (sandbox mode). Simulasi kirim template.', [
                'to' => $phone,
                'template' => $templateName,
                'parameters' => $parameters,
            ]);
            return false;
        }

        $config = self::getConfig();

        if (!$config['token'] || (!$config['phone_id'])) {
            Log::info('[DEMO WA SIMULATION] Kredensial Meta WA kosong. Simulasi kirim template.', [
                'to' => $phone,
                'template' => $templateName,
                'parameters' => $parameters,
            ]);
            return false;
        }

        // Clean phone number: remove non-digits, replace leading 0 with 62
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($phone) < 8) {
            Log::warning('Meta WA recipient phone is invalid: ' . $phone);
            return false;
        }
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Build components for template
        $components = [];
        if (!empty($parameters) && $templateName !== 'hello_world') {
            $componentParams = [];
            foreach ($parameters as $param) {
                $componentParams[] = [
                    'type' => 'text',
                    'text' => (string) $param
                ];
            }
            $components[] = [
                'type' => 'body',
                'parameters' => $componentParams
            ];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $phone,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => [
                    'code' => $templateName === 'hello_world' ? 'en_US' : 'id'
                ]
            ]
        ];

        if (!empty($components)) {
            $payload['template']['components'] = $components;
        }

        try {
            // Using API v19.0 as standard with 5 second timeout
            $response = Http::withoutVerifying()
                ->timeout(5)
                ->withToken($config['token'])
                ->post("https://graph.facebook.com/v19.0/{$config['phone_id']}/messages", $payload);

            if ($response->successful()) {
                return true;
            }

            Log::warning('Meta WA API failed', ['status' => $response->status(), 'response' => $response->json()]);
        } catch (\Throwable $e) {
            Log::error('Meta WA exception: ' . $e->getMessage());
        }

        return false;
    }

    public static function sendDonationPendingTemplate(Donation $donation): bool
    {
        $config = self::getConfig();
        $templateName = $config['template_name'];

        if (!$templateName) {
            Log::warning('Meta WA template name is missing');
            return false;
        }

        // Prepare parameters based on old Fonnte template
        // [nama, campaign, jumlah, bank, no_rek, atas_nama, batas_waktu]
        $parameters = [
            $donation->donor_name,
            $donation->campaign?->title ?? '-',
            number_format((float) $donation->amount, 0, ',', '.'),
            $donation->payment_bank ?: 'Bank Syariah Nasional',
            $donation->payment_account_number ?: '-',
            $donation->payment_account_name ?: '-',
            $donation->expires_at ? $donation->expires_at->translatedFormat('d M Y, H:i') : '-',
        ];

        return self::sendTemplate($donation->donor_phone, $templateName, $parameters);
    }

    public static function sendAdminDonationNotification(Donation $donation): bool
    {
        $config = self::getConfig();
        $templateName = $config['admin_template_name'];
        $adminPhone = $config['admin_phone'];

        if (!$templateName || !$adminPhone) {
            Log::warning('Meta WA admin template name or admin phone is missing');
            return false;
        }

        // Parameters for admin: [nama_donatur, jumlah, campaign, status]
        // This is just a suggested format, the user needs to configure their template to match these 4 parameters.
        $parameters = [
            $donation->donor_name,
            number_format((float) $donation->amount, 0, ',', '.'),
            $donation->campaign?->title ?? '-',
            'Pending (Menunggu Transfer)',
        ];

        return self::sendTemplate($adminPhone, $templateName, $parameters);
    }

    public static function sendDonationPaidTemplate(Donation $donation): bool
    {
        $config = self::getConfig();
        $templateName = $config['paid_template_name'];

        if (!$templateName) {
            Log::warning('Meta WA paid template name is missing, cannot send paid confirmation to ' . $donation->donor_phone);
            return false;
        }

        // Parameters: [nama_donatur, nama_campaign, jumlah]
        $parameters = [
            $donation->donor_name,
            $donation->campaign?->title ?? '-',
            number_format((float) $donation->amount, 0, ',', '.'),
        ];

        return self::sendTemplate($donation->donor_phone, $templateName, $parameters);
    }
}
