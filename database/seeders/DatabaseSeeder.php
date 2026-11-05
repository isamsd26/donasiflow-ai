<?php

namespace Database\Seeders;

use App\Models\FaqItem;
use App\Models\Setting;
use App\Models\StaticPage;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->seedAdminUser();
        $this->seedSettings();
        $this->seedStaticPages();
        $this->seedFaqItems();
        $this->call(PortfolioAnalyticsSeeder::class);
    }

    private function seedAdminUser(): void
    {
        if (app()->environment(['local', 'testing'])) {
            User::query()->updateOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'name' => 'Admin Utama',
                    'password' => Hash::make('password'),
                ]
            );

            return;
        }

        $email = env('ADMIN_INITIAL_EMAIL');
        $password = env('ADMIN_INITIAL_PASSWORD');

        if (! $email || ! $password) {
            return;
        }

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => env('ADMIN_INITIAL_NAME', 'Admin Utama'),
                'password' => Hash::make($password),
            ]
        );
    }

    private function seedSettings(): void
    {
        $settings = [
            ['group' => 'identity', 'key' => 'site_name', 'label' => 'Nama Situs', 'type' => 'text', 'value' => ['value' => 'DonasiFlow Nusantara']],
            ['group' => 'identity', 'key' => 'site_tagline', 'label' => 'Tagline Situs', 'type' => 'text', 'value' => ['value' => 'Campaign stories for real-world impact']],
            ['group' => 'impact', 'key' => 'impact_total_beneficiaries', 'label' => 'Total Penerima Manfaat', 'type' => 'text', 'value' => ['value' => '900,000']],
            ['group' => 'contact', 'key' => 'contact_email', 'label' => 'Email Kontak', 'type' => 'email', 'value' => ['value' => 'halo@donasiflow.org']],
            ['group' => 'contact', 'key' => 'whatsapp', 'label' => 'WhatsApp', 'type' => 'text', 'value' => ['value' => '081200001234']],
            ['group' => 'contact', 'key' => 'contact_address', 'label' => 'Alamat Kontak', 'type' => 'textarea', 'value' => ['value' => 'Jl. Pemuda No. 88, Kec. Semarang Tengah, Kota Semarang, Jawa Tengah 50132']],
            ['group' => 'contact', 'key' => 'contact_hours', 'label' => 'Jam Layanan', 'type' => 'text', 'value' => ['value' => 'Senin - Jumat, 09.00 - 17.00 WIB']],
            ['group' => 'payment', 'key' => 'payment_bank', 'label' => 'Bank Transfer Donasi', 'type' => 'text', 'value' => ['value' => 'Bank Syariah Nasional']],
            ['group' => 'payment', 'key' => 'payment_account_number', 'label' => 'Nomor Rekening Donasi', 'type' => 'text', 'value' => ['value' => '8899001122']],
            ['group' => 'payment', 'key' => 'payment_account_name', 'label' => 'Atas Nama Rekening Donasi', 'type' => 'text', 'value' => ['value' => 'Yayasan DonasiFlow Nusantara']],
            ['group' => 'social', 'key' => 'social_facebook', 'label' => 'Facebook', 'type' => 'url', 'value' => ['value' => 'https://facebook.com/donasiflow']],
            ['group' => 'social', 'key' => 'social_twitter', 'label' => 'Twitter/X', 'type' => 'url', 'value' => ['value' => 'https://x.com/donasiflow']],
            ['group' => 'social', 'key' => 'social_instagram', 'label' => 'Instagram', 'type' => 'url', 'value' => ['value' => 'https://instagram.com/donasiflow']],
            ['group' => 'social', 'key' => 'social_telegram', 'label' => 'Telegram', 'type' => 'url', 'value' => ['value' => 'https://t.me/donasiflow']],
            ['group' => 'footer', 'key' => 'footer_description', 'label' => 'Deskripsi Footer', 'type' => 'textarea', 'value' => ['value' => 'Platform donasi berbasis campaign dengan pengelolaan konten yang rapi, transparan, dan mudah diperbarui dari CMS admin.']],
            ['group' => 'footer', 'key' => 'footer_copyright', 'label' => 'Copyright Footer', 'type' => 'text', 'value' => ['value' => 'Copyright 2026 DonasiFlow Nusantara. Semua hak dilindungi.']],
            ['group' => 'footer', 'key' => 'footer_primary_cta_label', 'label' => 'Label CTA Footer', 'type' => 'text', 'value' => ['value' => 'Cek Status Donasi']],
            ['group' => 'footer', 'key' => 'footer_primary_cta_url', 'label' => 'URL CTA Footer', 'type' => 'text', 'value' => ['value' => '/donation/check']],
            ['group' => 'footer', 'key' => 'footer_navigation_menu', 'label' => 'Menu Footer Utama', 'type' => 'json', 'value' => [['label' => 'Campaign', 'url' => '/campaign'], ['label' => 'Tentang', 'url' => '/pages/about'], ['label' => 'Kontak', 'url' => '/pages/contact']]],
            ['group' => 'footer', 'key' => 'footer_support_menu', 'label' => 'Menu Footer Bantuan', 'type' => 'json', 'value' => [['label' => 'FAQ', 'url' => '/pages/faq'], ['label' => 'Tentang', 'url' => '/pages/about'], ['label' => 'Kebijakan Privasi', 'url' => '/pages/privacy-policy']]],
            ['group' => 'meta_wa', 'key' => 'meta_wa_enabled', 'label' => 'Enable Meta WA', 'type' => 'text', 'value' => ['value' => '0']],
            ['group' => 'meta_wa', 'key' => 'meta_wa_token', 'label' => 'Meta WA Token', 'type' => 'text', 'value' => ['value' => '']],
            ['group' => 'meta_wa', 'key' => 'meta_wa_phone_number_id', 'label' => 'Meta Phone Number ID', 'type' => 'text', 'value' => ['value' => '']],
            ['group' => 'meta_wa', 'key' => 'meta_wa_template_name', 'label' => 'Meta WA Template Name', 'type' => 'text', 'value' => ['value' => 'donasiflow_pending_notif']],
            ['group' => 'meta_wa', 'key' => 'meta_wa_admin_template_name', 'label' => 'Meta WA Admin Template Name', 'type' => 'text', 'value' => ['value' => 'donasiflow_admin_alert']],
            ['group' => 'meta_wa', 'key' => 'meta_wa_paid_template_name', 'label' => 'Meta WA Paid Template Name', 'type' => 'text', 'value' => ['value' => 'donasiflow_paid_receipt']],
        ];

        foreach ($settings as $setting) {
            Setting::query()->updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    private function seedStaticPages(): void
    {
        $pages = [
            [
                'slug' => 'about',
                'title' => 'Tentang DonasiFlow Nusantara',
                'intro' => 'Platform donasi berbasis campaign yang dirancang untuk alur publik yang jelas dan pengelolaan konten yang rapi.',
                'content' => "DonasiFlow Nusantara menghubungkan orang baik dengan program sosial yang dikelola secara terstruktur.\n\nSetiap campaign dapat diperbarui melalui CMS agar publik melihat progres yang relevan dan mudah dipahami.\n\nKami fokus pada kejelasan informasi, keteraturan operasional, dan pengalaman berdonasi yang hangat.",
            ],
            [
                'slug' => 'contact',
                'title' => 'Kontak dan Bantuan',
                'intro' => 'Hubungi tim kami untuk bantuan program, kerja sama, atau pertanyaan umum seputar campaign.',
                'content' => "Email: halo@donasiflow.org\nWhatsApp: 081200001234\nAlamat: Jl. Pemuda No. 88, Kec. Semarang Tengah, Kota Semarang, Jawa Tengah 50132\n\nJam layanan: Senin sampai Jumat, 09.00 - 17.00 WIB",
            ],
            [
                'slug' => 'faq',
                'title' => 'Pertanyaan yang Sering Ditanyakan',
                'intro' => 'Jawaban cepat untuk pertanyaan umum sebelum Anda mendukung campaign.',
                'content' => "Bagaimana memilih campaign? Anda dapat menggunakan filter status dan kategori pada halaman campaign.\n\nApakah bisa berdonasi anonim? Ya, donor dapat tampil anonim di publik.\n\nApakah campaign selesai tetap ditampilkan? Ya, campaign selesai tetap tampil sebagai arsip dampak.",
            ],
            [
                'slug' => 'privacy-policy',
                'title' => 'Kebijakan Privasi',
                'intro' => 'Penjelasan tentang data yang dikumpulkan, cara penggunaan, dan visibilitasnya di area publik.',
                'content' => "Kami mengumpulkan data dasar seperti nama, email, nomor telepon, dan data transaksi ketika donasi dilakukan.\n\nData digunakan untuk keperluan operasional platform, komunikasi program, dan pencatatan donasi.\n\nData donor hanya ditampilkan di publik jika memenuhi aturan visibilitas yang berlaku.",
            ],
        ];

        foreach ($pages as $page) {
            StaticPage::query()->updateOrCreate(
                ['slug' => $page['slug']],
                [
                    'title' => $page['title'],
                    'intro' => $page['intro'],
                    'content' => $page['content'],
                    'seo_title' => $page['title'],
                    'seo_description' => $page['intro'],
                    'is_published' => true,
                    'published_at' => now(),
                ]
            );
        }

        StaticPage::query()->where('slug', 'hak-donatur')->delete();
    }

    private function seedFaqItems(): void
    {
        $items = [
            [
                'question' => 'Bagaimana memilih campaign yang tepat untuk didukung?',
                'answer' => 'Anda dapat memilih campaign berdasarkan kategori, status, lokasi, serta ringkasan tujuan program pada halaman campaign publik.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'Apakah donor bisa tampil anonim di website?',
                'answer' => 'Bisa. Saat mengisi data donasi, donor dapat memilih tampil anonim pada area publik.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'Apakah campaign yang sudah selesai tetap ditampilkan?',
                'answer' => 'Ya. Campaign yang selesai tetap dapat ditampilkan sebagai arsip dampak dan dokumentasi program.',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            FaqItem::query()->updateOrCreate(
                ['question' => $item['question']],
                $item
            );
        }
    }
}
