<?php

namespace App\Support;

class AdminSite
{
    public static function shell(): array
    {
        return [
            'title' => 'CMS Admin',
            'brand' => 'DonasiFlow Admin',
            'navigation' => [
                [
                    'section' => 'Ringkasan',
                    'description' => 'Pantau kondisi website dan aktivitas utama.',
                    'items' => [
                        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => 'dashboard'],
                    ],
                ],
                [
                    'section' => 'Transaksi',
                    'description' => 'Lihat donasi masuk dan data pendukungnya.',
                    'items' => [
                        ['label' => 'Transaksi Donasi', 'route' => 'admin.donations', 'active' => 'admin.donations*', 'icon' => 'transactions'],
                        ['label' => 'Donor', 'route' => 'admin.donors', 'active' => 'admin.donors*', 'icon' => 'donors'],
                    ],
                ],
                [
                    'section' => 'Konten Website',
                    'description' => 'Atur isi website yang tampil ke publik.',
                    'items' => [
                        ['label' => 'Campaign', 'route' => 'admin.campaigns', 'active' => 'admin.campaigns*', 'icon' => 'campaign'],
                        ['label' => 'Kategori', 'route' => 'admin.categories', 'active' => 'admin.categories*', 'icon' => 'category'],
                        ['label' => 'Update Program', 'route' => 'admin.updates', 'active' => 'admin.updates*', 'icon' => 'updates'],
                        ['label' => 'Banner Utama', 'route' => 'admin.banners', 'active' => 'admin.banners*', 'icon' => 'banner'],
                        ['label' => 'FAQ', 'route' => 'admin.faqs', 'active' => 'admin.faqs*', 'icon' => 'faq'],
                        ['label' => 'Testimoni', 'route' => 'admin.testimonials', 'active' => 'admin.testimonials*', 'icon' => 'testimonial'],
                        ['label' => 'Halaman Website', 'route' => 'admin.pages', 'active' => 'admin.pages*', 'icon' => 'pages'],
                    ],
                ],
                [
                    'section' => 'Komunikasi',
                    'description' => 'Kelola interaksi dan pesan dengan donatur.',
                    'items' => [
                        ['label' => 'WA Balasan', 'route' => 'admin.wa-reply.index', 'active' => '*wa-*', 'icon' => 'faq'],
                    ],
                ],
                [
                    'section' => 'Pengaturan',
                    'description' => 'Atur identitas, footer, kontak, dan sosial media.',
                    'items' => [
                        ['label' => 'Pengaturan Website', 'route' => 'admin.settings', 'active' => 'admin.settings*', 'icon' => 'settings'],
                    ],
                ],
            ],
        ];
    }

    public static function page(string $key): array
    {
        return match ($key) {
            'campaigns' => [
                'title' => 'Campaign',
                'description' => 'Kelola campaign yang tampil di website, termasuk status, target, dan urutan tampilnya.',
            ],
            'categories' => [
                'title' => 'Kategori',
                'description' => 'Kelola kategori untuk membantu mengelompokkan campaign di website.',
            ],
            'updates' => [
                'title' => 'Update Program',
                'description' => 'Kelola kabar terbaru yang tampil di halaman detail campaign.',
            ],
            'donations' => [
                'title' => 'Transaksi Donasi',
                'description' => 'Pantau donasi, status pembayaran, dan riwayat transaksi yang masuk ke sistem.',
            ],
            'donors' => [
                'title' => 'Donor',
                'description' => 'Lihat data donor dan ringkasan histori kontribusi mereka.',
            ],
            'banners' => [
                'title' => 'Banner Utama',
                'description' => 'Kelola banner unggulan yang tampil di bagian atas homepage.',
            ],
            'faqs' => [
                'title' => 'FAQ',
                'description' => 'Kelola pertanyaan dan jawaban yang sering ditanyakan pengunjung.',
            ],
            'testimonials' => [
                'title' => 'Testimoni',
                'description' => 'Kelola testimoni yang tampil di homepage dan area publik lainnya.',
            ],
            'pages' => [
                'title' => 'Halaman Website',
                'description' => 'Kelola halaman informasi seperti Tentang, Kontak, dan Privacy Policy.',
            ],
            'settings' => [
                'title' => 'Pengaturan Website',
                'description' => 'Kelola identitas situs, footer, kontak, sosial media, dan pengaturan umum website.',
            ],
            default => [
                'title' => 'Dashboard',
                'description' => 'Ringkasan operasional CMS admin untuk campaign, transaksi, donor, dan konten publik.',
            ],
        };
    }

    public static function dashboard(): array
    {
        return [
            'stats' => [
                ['label' => 'Campaign Aktif', 'value' => '12', 'note' => 'Campaign yang masih menerima donasi'],
                ['label' => 'Donasi Masuk', 'value' => 'Rp 1,8 M', 'note' => 'Akumulasi publik saat ini'],
                ['label' => 'Pending Hari Ini', 'value' => '18', 'note' => 'Transaksi yang menunggu penyelesaian'],
                ['label' => 'Donor Tercatat', 'value' => '2.430', 'note' => 'Data donor di sistem'],
            ],
            'recent_transactions' => [
                ['reference' => 'INV-20260324-001', 'campaign' => 'Pangan Pekanan', 'donor' => 'Anonim', 'amount' => 'Rp 250.000', 'status' => 'Paid'],
                ['reference' => 'INV-20260324-002', 'campaign' => 'Jariyah Air', 'donor' => 'Rahmawati', 'amount' => 'Rp 500.000', 'status' => 'Pending'],
                ['reference' => 'INV-20260324-003', 'campaign' => 'Teman Pulih', 'donor' => 'Budi Prasetyo', 'amount' => 'Rp 150.000', 'status' => 'Paid'],
            ],
            'ending_campaigns' => [
                ['title' => 'Peluk Yatim', 'days_left' => '14 hari lagi', 'progress' => '61%'],
                ['title' => 'Ibu Hebat', 'days_left' => '17 hari lagi', 'progress' => '67%'],
                ['title' => 'Sahur Pesisir', 'days_left' => '19 hari lagi', 'progress' => '66%'],
            ],
        ];
    }

    public static function module(string $type): array
    {
        return match ($type) {
            'campaigns' => [
                'title' => 'Manajemen Campaign',
                'description' => 'Kelola campaign, status publik, target, featured home, dan metadata khusus.',
                'columns' => ['Judul', 'Kategori', 'Publish', 'Status', 'Target', 'Terkumpul', 'Akhir'],
                'rows' => [
                    ['Pangan Pekanan', 'Education', 'Published', 'Aktif', 'Rp 150.000.000', 'Rp 84.215.000', '25 hari lagi'],
                    ['Jariyah Air', 'Humanity', 'Published', 'Aktif', 'Rp 240.000.000', 'Rp 189.720.000', '58 hari lagi'],
                    ['Ibu Mandiri', 'Education', 'Archived', 'Selesai', 'Rp 69.320.000', 'Rp 69.320.000', '62 hari lalu'],
                ],
                'actions' => ['Tambah Campaign', 'Preview Publik', 'Export Data'],
            ],
            'categories' => [
                'title' => 'Kategori Campaign',
                'description' => 'Kelola taxonomy utama seperti education dan humanity.',
                'columns' => ['Nama', 'Slug', 'Aktif', 'Jumlah Campaign'],
                'rows' => [
                    ['Education', 'education', 'Ya', '7'],
                    ['Humanity', 'humanity', 'Ya', '11'],
                ],
                'actions' => ['Tambah Kategori'],
            ],
            'updates' => [
                'title' => 'Update Campaign',
                'description' => 'Kelola kabar terbaru yang tampil pada tab campaign detail.',
                'columns' => ['Judul Update', 'Campaign', 'Tanggal', 'Publish'],
                'rows' => [
                    ['Distribusi tahap awal dimulai', 'Pangan Pekanan', '18 Maret 2026', 'Published'],
                    ['Monitoring kebutuhan lapangan', 'Teman Pulih', '10 Maret 2026', 'Draft'],
                ],
                'actions' => ['Tambah Update', 'Filter Campaign'],
            ],
            'donations' => [
                'title' => 'Donasi dan Transaksi',
                'description' => 'Pantau status transaksi, nominal, campaign terkait, dan kebutuhan export laporan.',
                'columns' => ['Reference', 'Campaign', 'Donor', 'Nominal', 'Status', 'Waktu'],
                'rows' => [
                    ['INV-20260324-001', 'Pangan Pekanan', 'Anonim', 'Rp 250.000', 'Paid', '24 Mar 2026 09:10'],
                    ['INV-20260324-002', 'Jariyah Air', 'Rahmawati', 'Rp 500.000', 'Pending', '24 Mar 2026 10:22'],
                    ['INV-20260324-003', 'Teman Pulih', 'Budi Prasetyo', 'Rp 150.000', 'Paid', '24 Mar 2026 11:03'],
                ],
                'actions' => ['Filter Status', 'Export CSV', 'Lihat Detail'],
            ],
            'donors' => [
                'title' => 'Donor',
                'description' => 'Kelola data donor, histori donasi, dan visibilitas anonim di publik.',
                'columns' => ['Nama', 'Email', 'Telepon', 'Total Donasi', 'Anonim Publik'],
                'rows' => [
                    ['Rahmawati', 'rahmawati@example.test', '0812-1111-0001', 'Rp 2.450.000', 'Tidak'],
                    ['Anonim', 'hidden@example.test', '0812-1111-0002', 'Rp 1.350.000', 'Ya'],
                    ['Budi Prasetyo', 'budi@example.test', '0812-1111-0003', 'Rp 850.000', 'Tidak'],
                ],
                'actions' => ['Cari Donor', 'Lihat Histori'],
            ],
            'banners' => [
                'title' => 'Hero Banner',
                'description' => 'Kelola hero slider atau campaign featured yang tampil di home.',
                'columns' => ['Judul', 'CTA', 'Aktif', 'Urutan'],
                'rows' => [
                    ['Ruang Aman Ibu Tangguh', 'Lihat Program', 'Ya', '1'],
                    ['Paket Belajar Cerah', 'Buka Campaign', 'Ya', '2'],
                    ['Sehat Sampai Pelosok', 'Lihat Detail', 'Ya', '3'],
                ],
                'actions' => ['Tambah Banner', 'Ubah Urutan'],
            ],
            'pages' => [
                'title' => 'Halaman Statis',
                'description' => 'Kelola konten halaman informasi seperti Tentang, Kontak, FAQ, dan Privacy Policy.',
                'columns' => ['Judul', 'Slug', 'Published', 'Terakhir Diperbarui'],
                'rows' => [
                    ['Tentang DonasiFlow Nusantara', 'about', 'Ya', '24 Mar 2026'],
                    ['Kontak dan Bantuan', 'contact', 'Ya', '24 Mar 2026'],
                    ['FAQ', 'faq', 'Ya', '24 Mar 2026'],
                ],
                'actions' => ['Tambah Halaman', 'Edit SEO'],
            ],
            default => [
                'title' => 'Settings',
                'description' => 'Kelola identitas situs, kontak, sosial media, meta SEO, dan konfigurasi sistem.',
                'sections' => [
                    ['title' => 'Identitas Situs', 'items' => ['Nama situs', 'Tagline', 'Logo', 'Favicon']],
                    ['title' => 'Kontak', 'items' => ['Email utama', 'Nomor WhatsApp', 'Alamat singkat']],
                    ['title' => 'Sosial Media', 'items' => ['Facebook', 'Instagram', 'Twitter/X', 'Telegram']],
                    ['title' => 'SEO Dasar', 'items' => ['Meta title', 'Meta description', 'Open Graph image']],
                ],
                'actions' => ['Simpan Perubahan'],
            ],
        };
    }
}
