<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends AdminController
{
    public function index(): View
    {
        $this->seedDefaults();

        $definitions = $this->definitions();

        return view('admin.settings.index', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('settings'),
            'sections' => $this->sections(),
            'values' => $this->settingValues($definitions),
            'menuExamples' => [
                'navigation' => "Campaign|/campaign\nTentang|/pages/about\nKontak|/pages/contact",
                'support' => "FAQ|/pages/faq\nTentang|/pages/about\nKebijakan Privasi|/pages/privacy-policy",
            ],
        ]);
    }

    public function save(Request $request): RedirectResponse
    {
        $this->seedDefaults();

        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'impact_total_beneficiaries' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:255'],
            'contact_address' => ['nullable', 'string'],
            'contact_hours' => ['nullable', 'string', 'max:255'],
            'payment_bank' => ['required', 'string', 'max:255'],
            'payment_account_number' => ['required', 'string', 'max:255'],
            'payment_account_name' => ['required', 'string', 'max:255'],
            'social_facebook' => ['nullable', 'url', 'max:255'],
            'social_twitter' => ['nullable', 'url', 'max:255'],
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'social_telegram' => ['nullable', 'url', 'max:255'],
            'footer_description' => ['nullable', 'string'],
            'footer_copyright' => ['nullable', 'string', 'max:255'],
            'footer_primary_cta_label' => ['nullable', 'string', 'max:255'],
            'footer_primary_cta_url' => ['nullable', 'string', 'max:255'],
            'footer_navigation_menu_text' => ['nullable', 'string'],
            'footer_support_menu_text' => ['nullable', 'string'],
            'meta_wa_token' => ['nullable', 'string', 'max:1000'],
            'meta_wa_phone_number_id' => ['nullable', 'string', 'max:255'],
            'meta_wa_enabled' => ['nullable', 'string', 'max:255'],
            'meta_wa_template_name' => ['nullable', 'string', 'max:255'],
            'meta_wa_admin_template_name' => ['nullable', 'string', 'max:255'],
            'meta_wa_paid_template_name' => ['nullable', 'string', 'max:255'],
        ]);

        $definitions = $this->definitions();

        foreach ($definitions as $key => $definition) {
            $value = match ($key) {
                'footer_navigation_menu' => $this->parseMenuLines($validated['footer_navigation_menu_text'] ?? ''),
                'footer_support_menu' => $this->parseMenuLines($validated['footer_support_menu_text'] ?? ''),
                default => ['value' => $validated[$key] ?? ''],
            };

            Setting::query()->updateOrCreate(
                ['key' => $key],
                [
                    'group' => $definition['group'],
                    'label' => $definition['label'],
                    'type' => $definition['type'],
                    'value' => $value,
                ]
            );
        }

        return redirect()->route('admin.settings')->with('success', 'Pengaturan website berhasil diperbarui.');
    }

    public function create(): View
    {
        return view('admin.settings.form', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('settings', 'Tambah Setting', 'Tambahkan konfigurasi baru untuk identitas situs, kontak, atau SEO umum.'),
            'setting' => new Setting(['type' => 'text']),
            'valueText' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Setting::query()->create($this->validateSetting($request));

        return redirect()->route('admin.settings')->with('success', 'Setting berhasil ditambahkan.');
    }

    public function edit(Setting $setting): View
    {
        return view('admin.settings.form', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('settings', 'Edit Setting', 'Perbarui nilai konfigurasi yang dipakai di situs dan CMS.'),
            'setting' => $setting,
            'valueText' => $this->valueText($setting),
        ]);
    }

    public function update(Request $request, Setting $setting): RedirectResponse
    {
        $setting->update($this->validateSetting($request, $setting));

        return redirect()->route('admin.settings')->with('success', 'Setting berhasil diperbarui.');
    }

    public function destroy(Setting $setting): RedirectResponse
    {
        $setting->delete();

        return redirect()->route('admin.settings')->with('success', 'Setting berhasil dihapus.');
    }

    protected function validateSetting(Request $request, ?Setting $setting = null): array
    {
        $validated = $request->validate([
            'group' => ['nullable', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:255', Rule::unique('settings', 'key')->ignore($setting?->id)],
            'label' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['text', 'textarea', 'url', 'email', 'json'])],
            'value_text' => ['nullable', 'string'],
        ]);

        $value = ['value' => $validated['value_text'] ?? ''];

        if ($validated['type'] === 'json' && filled($validated['value_text'] ?? null)) {
            $decoded = json_decode($validated['value_text'], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()
                    ->withErrors(['value_text' => 'Nilai setting bertipe JSON harus berupa JSON yang valid.'])
                    ->withInput()
                    ->throwResponse();
            }

            $value = $decoded;
        }

        return [
            'group' => $validated['group'] ?? null,
            'key' => $validated['key'],
            'label' => $validated['label'],
            'type' => $validated['type'],
            'value' => $value,
        ];
    }

    protected function valueText(Setting $setting): ?string
    {
        if ($setting->type === 'json') {
            return json_encode($setting->value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        return $setting->value['value'] ?? null;
    }

    protected function seedDefaults(): void
    {
        foreach ($this->definitions() as $key => $default) {
            Setting::query()->firstOrCreate(
                ['key' => $key],
                $default
            );
        }
    }

    protected function definitions(): array
    {
        return [
            'site_name' => ['group' => 'identity', 'key' => 'site_name', 'label' => 'Nama Situs', 'type' => 'text', 'value' => ['value' => 'DonasiFlow Nusantara']],
            'site_tagline' => ['group' => 'identity', 'key' => 'site_tagline', 'label' => 'Tagline Situs', 'type' => 'text', 'value' => ['value' => 'Campaign stories for real-world impact']],
            'impact_total_beneficiaries' => ['group' => 'impact', 'key' => 'impact_total_beneficiaries', 'label' => 'Total Penerima Manfaat', 'type' => 'text', 'value' => ['value' => '900,000']],
            'contact_email' => ['group' => 'contact', 'key' => 'contact_email', 'label' => 'Email Kontak', 'type' => 'email', 'value' => ['value' => 'halo@donasiflow.org']],
            'whatsapp' => ['group' => 'contact', 'key' => 'whatsapp', 'label' => 'WhatsApp', 'type' => 'text', 'value' => ['value' => '081200001234']],
            'contact_address' => ['group' => 'contact', 'key' => 'contact_address', 'label' => 'Alamat Kontak', 'type' => 'textarea', 'value' => ['value' => 'Jl. Pemuda No. 88, Kec. Semarang Tengah, Kota Semarang, Jawa Tengah 50132']],
            'contact_hours' => ['group' => 'contact', 'key' => 'contact_hours', 'label' => 'Jam Layanan', 'type' => 'text', 'value' => ['value' => 'Senin - Jumat, 09.00 - 17.00 WIB']],
            'payment_bank' => ['group' => 'payment', 'key' => 'payment_bank', 'label' => 'Bank Transfer Donasi', 'type' => 'text', 'value' => ['value' => 'Bank Syariah Nasional']],
            'payment_account_number' => ['group' => 'payment', 'key' => 'payment_account_number', 'label' => 'Nomor Rekening Donasi', 'type' => 'text', 'value' => ['value' => '8899001122']],
            'payment_account_name' => ['group' => 'payment', 'key' => 'payment_account_name', 'label' => 'Atas Nama Rekening Donasi', 'type' => 'text', 'value' => ['value' => 'Yayasan DonasiFlow Nusantara']],
            'social_facebook' => ['group' => 'social', 'key' => 'social_facebook', 'label' => 'Facebook', 'type' => 'url', 'value' => ['value' => 'https://facebook.com/donasiflow']],
            'social_twitter' => ['group' => 'social', 'key' => 'social_twitter', 'label' => 'Twitter/X', 'type' => 'url', 'value' => ['value' => 'https://x.com/donasiflow']],
            'social_instagram' => ['group' => 'social', 'key' => 'social_instagram', 'label' => 'Instagram', 'type' => 'url', 'value' => ['value' => 'https://instagram.com/donasiflow']],
            'social_telegram' => ['group' => 'social', 'key' => 'social_telegram', 'label' => 'Telegram', 'type' => 'url', 'value' => ['value' => 'https://t.me/donasiflow']],
            'footer_description' => ['group' => 'footer', 'key' => 'footer_description', 'label' => 'Deskripsi Footer', 'type' => 'textarea', 'value' => ['value' => 'Platform donasi berbasis campaign dengan pengelolaan konten yang rapi, transparan, dan mudah diperbarui dari CMS admin.']],
            'footer_copyright' => ['group' => 'footer', 'key' => 'footer_copyright', 'label' => 'Copyright Footer', 'type' => 'text', 'value' => ['value' => 'Copyright 2026 DonasiFlow Nusantara. Semua hak dilindungi.']],
            'footer_primary_cta_label' => ['group' => 'footer', 'key' => 'footer_primary_cta_label', 'label' => 'Label CTA Footer', 'type' => 'text', 'value' => ['value' => 'Cek Status Donasi']],
            'footer_primary_cta_url' => ['group' => 'footer', 'key' => 'footer_primary_cta_url', 'label' => 'URL CTA Footer', 'type' => 'text', 'value' => ['value' => '/donation/check']],
            'footer_navigation_menu' => ['group' => 'footer', 'key' => 'footer_navigation_menu', 'label' => 'Menu Footer Utama', 'type' => 'json', 'value' => [['label' => 'Campaign', 'url' => '/campaign'], ['label' => 'Tentang', 'url' => '/pages/about'], ['label' => 'Kontak', 'url' => '/pages/contact']]],
            'footer_support_menu' => ['group' => 'footer', 'key' => 'footer_support_menu', 'label' => 'Menu Footer Bantuan', 'type' => 'json', 'value' => [['label' => 'FAQ', 'url' => '/pages/faq'], ['label' => 'Tentang', 'url' => '/pages/about'], ['label' => 'Kebijakan Privasi', 'url' => '/pages/privacy-policy']]],
            'meta_wa_token' => ['group' => 'meta_wa', 'key' => 'meta_wa_token', 'label' => 'Meta WA Token', 'type' => 'text', 'value' => ['value' => '']],
            'meta_wa_phone_number_id' => ['group' => 'meta_wa', 'key' => 'meta_wa_phone_number_id', 'label' => 'Meta Phone Number ID', 'type' => 'text', 'value' => ['value' => '']],
            'meta_wa_enabled' => ['group' => 'meta_wa', 'key' => 'meta_wa_enabled', 'label' => 'Enable Meta WA', 'type' => 'text', 'value' => ['value' => '0']],
            'meta_wa_template_name' => ['group' => 'meta_wa', 'key' => 'meta_wa_template_name', 'label' => 'Meta WA Template Name', 'type' => 'text', 'value' => ['value' => 'donasiflow_pending_notif']],
            'meta_wa_admin_template_name' => ['group' => 'meta_wa', 'key' => 'meta_wa_admin_template_name', 'label' => 'Meta WA Admin Template Name', 'type' => 'text', 'value' => ['value' => 'donasiflow_admin_alert']],
            'meta_wa_paid_template_name' => ['group' => 'meta_wa', 'key' => 'meta_wa_paid_template_name', 'label' => 'Meta WA Paid Template Name', 'type' => 'text', 'value' => ['value' => 'donasiflow_paid_receipt']],
        ];
    }

    protected function sections(): array
    {
        return [
            [
                'title' => 'Identitas Website',
                'description' => 'Atur nama situs, tagline, dan angka global yang tampil di homepage.',
                'fields' => [
                    ['key' => 'site_name', 'label' => 'Nama Situs', 'type' => 'text'],
                    ['key' => 'site_tagline', 'label' => 'Tagline Situs', 'type' => 'text'],
                    ['key' => 'impact_total_beneficiaries', 'label' => 'Total Penerima Manfaat', 'type' => 'text'],
                ],
            ],
            [
                'title' => 'Kontak',
                'description' => 'Data ini dipakai di footer dan halaman kontak publik.',
                'fields' => [
                    ['key' => 'contact_email', 'label' => 'Email Kontak', 'type' => 'email'],
                    ['key' => 'whatsapp', 'label' => 'WhatsApp', 'type' => 'text'],
                    ['key' => 'contact_hours', 'label' => 'Jam Layanan', 'type' => 'text'],
                    ['key' => 'contact_address', 'label' => 'Alamat Kontak', 'type' => 'textarea', 'full' => true],
                ],
            ],
            [
                'title' => 'Rekening Transfer Donasi',
                'description' => 'Data ini tampil pada halaman instruksi transfer setelah donatur mengirim form donasi.',
                'fields' => [
                    ['key' => 'payment_bank', 'label' => 'Bank', 'type' => 'text'],
                    ['key' => 'payment_account_number', 'label' => 'Nomor Rekening', 'type' => 'text'],
                    ['key' => 'payment_account_name', 'label' => 'Atas Nama', 'type' => 'text', 'full' => true],
                ],
            ],
            [
                'title' => 'Sosial Media',
                'description' => 'Isi hanya kanal yang memang digunakan publik.',
                'fields' => [
                    ['key' => 'social_facebook', 'label' => 'Facebook', 'type' => 'url'],
                    ['key' => 'social_twitter', 'label' => 'Twitter/X', 'type' => 'url'],
                    ['key' => 'social_instagram', 'label' => 'Instagram', 'type' => 'url'],
                    ['key' => 'social_telegram', 'label' => 'Telegram', 'type' => 'url'],
                ],
            ],
            [
                'title' => 'Footer',
                'description' => 'Konten footer global agar admin tidak perlu membuat halaman atau item tambahan.',
                'fields' => [
                    ['key' => 'footer_description', 'label' => 'Deskripsi Footer', 'type' => 'textarea', 'full' => true],
                    ['key' => 'footer_copyright', 'label' => 'Copyright Footer', 'type' => 'text'],
                    ['key' => 'footer_primary_cta_label', 'label' => 'Label Tombol Footer', 'type' => 'text'],
                    ['key' => 'footer_primary_cta_url', 'label' => 'URL Tombol Footer', 'type' => 'text'],
                    ['key' => 'footer_navigation_menu_text', 'label' => 'Menu Footer Utama', 'type' => 'textarea', 'full' => true, 'help' => 'Satu baris satu menu dengan format: Label|/url'],
                    ['key' => 'footer_support_menu_text', 'label' => 'Menu Footer Bantuan', 'type' => 'textarea', 'full' => true, 'help' => 'Satu baris satu menu dengan format: Label|/url'],
                ],
            ],
            [
                'title' => 'Integrasi Meta WhatsApp Cloud API',
                'description' => 'Konfigurasi notifikasi WA otomatis dari Meta resmi.',
                'fields' => [
                    ['key' => 'meta_wa_enabled', 'label' => 'Aktifkan Meta WA (1 = Ya, 0 = Tidak)', 'type' => 'text'],
                    ['key' => 'meta_wa_token', 'label' => 'Meta WA Access Token', 'type' => 'text'],
                    ['key' => 'meta_wa_phone_number_id', 'label' => 'Phone Number ID', 'type' => 'text'],
                    ['key' => 'meta_wa_template_name', 'label' => 'Nama Template (Donasi Pending — ke Donatur)', 'type' => 'text', 'help' => 'Harus sama persis dengan nama template di dashboard Meta. Menerima 7 parameter.'],
                    ['key' => 'meta_wa_admin_template_name', 'label' => 'Nama Template (Donasi Pending — ke Admin)', 'type' => 'text', 'help' => 'Nama template khusus notifikasi ke Admin.'],
                    ['key' => 'meta_wa_paid_template_name', 'label' => 'Nama Template (Donasi Berhasil — ke Donatur)', 'type' => 'text', 'help' => 'Template yang dikirim ke donatur saat admin mengonfirmasi donasi sebagai paid. Menerima 3 parameter: nama, campaign, jumlah.'],
                ],
            ],
        ];
    }

    protected function settingValues(array $definitions): array
    {
        $settings = Setting::query()
            ->whereIn('key', array_keys($definitions))
            ->get()
            ->keyBy('key');

        $values = [];

        foreach ($definitions as $key => $definition) {
            $setting = $settings->get($key);
            $values[$key] = $setting ? $this->valueText($setting) : $this->defaultTextValue($definition);
        }

        $values['footer_navigation_menu_text'] = $this->menuLines($settings->get('footer_navigation_menu')?->value ?? $definitions['footer_navigation_menu']['value']);
        $values['footer_support_menu_text'] = $this->menuLines($settings->get('footer_support_menu')?->value ?? $definitions['footer_support_menu']['value']);

        return $values;
    }

    protected function defaultTextValue(array $definition): ?string
    {
        if (($definition['type'] ?? null) === 'json') {
            return null;
        }

        return $definition['value']['value'] ?? null;
    }

    protected function menuLines(array $items): string
    {
        return collect($items)
            ->map(fn ($item) => trim((string) ($item['label'] ?? '')).'|'.trim((string) ($item['url'] ?? '')))
            ->filter(fn ($line) => $line !== '|')
            ->implode("\n");
    }

    protected function parseMenuLines(string $input): array
    {
        return collect(preg_split("/\r\n|\n|\r/", trim($input)))
            ->map(fn ($line) => trim((string) $line))
            ->filter()
            ->map(function (string $line) {
                $parts = array_map('trim', explode('|', $line, 2));

                return [
                    'label' => $parts[0] ?? '',
                    'url' => $parts[1] ?? '',
                ];
            })
            ->filter(fn (array $item) => $item['label'] !== '' && $item['url'] !== '')
            ->values()
            ->all();
    }
}
