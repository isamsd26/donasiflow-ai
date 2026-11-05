<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Models\CampaignUpdate;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\HeroBanner;
use App\Models\PaymentTransaction;
use App\Models\Testimonial;
use App\Models\User;
use App\Support\CampaignMetrics;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PortfolioAnalyticsSeeder extends Seeder
{
    public function run(): void
    {
        mt_srand(20261105);

        $adminId = User::query()->value('id');
        $categories = $this->seedCategories();
        $campaigns = $this->seedCampaigns($categories);
        $this->seedCampaignUpdates($campaigns);
        $this->seedHeroBanners($campaigns);
        $this->seedTestimonials();

        $donors = $this->seedDonors();
        $this->seedHistoricalDonations($campaigns, $donors, $adminId);

        foreach ($campaigns as $campaign) {
            CampaignMetrics::sync($campaign);
        }
    }

    private function seedCategories(): array
    {
        $definitions = [
            'education' => [
                'name' => 'Pendidikan & Beasiswa',
                'slug' => 'education',
                'description' => 'Program beasiswa pelajar dhuafa, renovasi ruang belajar, dan literasi masyarakat.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            'humanity' => [
                'name' => 'Kemanusiaan & Pangan',
                'slug' => 'humanity',
                'description' => 'Program bantuan sosial, ketahanan pangan keluarga, dan tanggap darurat.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            'health' => [
                'name' => 'Kesehatan & Medis',
                'slug' => 'health',
                'description' => 'Pendampingan pasien dhuafa, layanan klinik keliling, dan pemulihan gizi anak.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            'infrastructure' => [
                'name' => 'Infrastruktur & Air Bersih',
                'slug' => 'infrastructure',
                'description' => 'Pembangunan sumur bor, jaringan air bersih komunal, dan sanitasi desa.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            'empowerment' => [
                'name' => 'Pemberdayaan Ekonomi',
                'slug' => 'empowerment',
                'description' => 'Bantuan modal usaha mikro, pelatihan keterampilan ibu mandiri, dan UMKM.',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        $result = [];
        foreach ($definitions as $key => $payload) {
            $result[$key] = CampaignCategory::query()->updateOrCreate(
                ['slug' => $payload['slug']],
                $payload
            );
        }

        return $result;
    }

    private function seedCampaigns(array $categories): array
    {
        $organizer = 'Yayasan DonasiFlow Nusantara';

        $definitions = [
            'pangan-pekanan' => [
                'campaign_category_id' => $categories['humanity']->id,
                'title' => 'Paket Pangan Pekanan Keluarga Rentan',
                'slug' => 'pangan-pekanan',
                'organizer_name' => $organizer,
                'featured_badge' => 'Pangan Pekanan',
                'cta_label' => 'Donasi Sekarang',
                'location' => 'Semarang & Demak',
                'focus_area' => 'Paket bahan pokok keluarga',
                'distribution_plan' => 'Distribusi setiap pekan',
                'summary' => 'Distribusi paket pangan rutin untuk keluarga prasejahtera dan lansia di kawasan padat penduduk.',
                'content' => "Banyak keluarga prasejahtera bertahan dengan pengeluaran harian yang sangat terbatas untuk mencukupi kebutuhan gizi dasar.\n\nMelalui program Paket Pangan Pekanan, DonasiFlow Nusantara menyalurkan beras, protein, dan bahan pokok setiap hari Jumat secara terukur dan terverifikasi.",
                'target_amount' => 180000000,
                'current_amount' => 0,
                'donor_count' => 0,
                'start_date' => now()->subDays(120)->toDateString(),
                'end_date' => now()->addDays(25)->toDateString(),
                'publish_status' => 'published',
                'public_status' => 'active',
                'is_featured' => true,
                'display_order' => 1,
                'meta' => null,
            ],
            'jariyah-air' => [
                'campaign_category_id' => $categories['infrastructure']->id,
                'title' => 'Jariyah Infrastruktur Air Bersih Desa',
                'slug' => 'jariyah-air',
                'organizer_name' => $organizer,
                'featured_badge' => 'Jariyah Air',
                'cta_label' => 'Donasi Sekarang',
                'location' => 'Grobogan & Gunungkidul',
                'focus_area' => 'Sumur bor & jaringan pipa komunal',
                'distribution_plan' => 'Bertahap per titik sumur',
                'summary' => 'Pembangunan instalasi sumur bor dan tandon air bersih untuk desa yang mengalami kekeringan tahunan.',
                'content' => "Akses air bersih masih menjadi tantangan berat bagi ratusan kepala keluarga di wilayah perbukitan kapur.\n\nProgram ini menghadirkan sumur bor dalam beserta jaringan pipanisasi komunal yang dikelola langsung bersama warga setempat.",
                'target_amount' => 200000000,
                'current_amount' => 0,
                'donor_count' => 0,
                'start_date' => now()->subDays(150)->toDateString(),
                'end_date' => now()->addDays(45)->toDateString(),
                'publish_status' => 'published',
                'public_status' => 'active',
                'is_featured' => true,
                'display_order' => 2,
                'meta' => null,
            ],
            'teman-pulih' => [
                'campaign_category_id' => $categories['health']->id,
                'title' => 'Teman Pulih Pasien Dhuafa',
                'slug' => 'teman-pulih',
                'organizer_name' => $organizer,
                'featured_badge' => 'Teman Pulih',
                'cta_label' => 'Donasi Sekarang',
                'location' => 'Kota Semarang & Sekitarnya',
                'focus_area' => 'Rumah singgah, nutrisi & ambulans gratis',
                'distribution_plan' => 'Sesuai rujukan medis pasien',
                'summary' => 'Bantuan biaya transportasi medis, suplemen nutrisi, dan pendampingan pasien rujukan dari keluarga dhuafa.',
                'content' => "Biaya non-medis seperti transportasi antarkota dan nutrisi pemulihan sering kali membuat pasien dhuafa menghentikan pengobatan.\n\nCampaign ini memastikan pasien rujukan tetap bisa menjalani terapi hingga tuntas tanpa terbebani biaya operasional harian.",
                'target_amount' => 138000000,
                'current_amount' => 0,
                'donor_count' => 0,
                'start_date' => now()->subDays(95)->toDateString(),
                'end_date' => now()->addDays(35)->toDateString(),
                'publish_status' => 'published',
                'public_status' => 'active',
                'is_featured' => true,
                'display_order' => 3,
                'meta' => null,
            ],
            'beasiswa-santri-pelosok' => [
                'campaign_category_id' => $categories['education']->id,
                'title' => 'Beasiswa Pelajar & Santri Pelosok Nusantara',
                'slug' => 'beasiswa-santri-pelosok',
                'organizer_name' => $organizer,
                'featured_badge' => 'Beasiswa Pelosok',
                'cta_label' => 'Donasi Sekarang',
                'location' => 'Jawa Tengah & Nusa Tenggara',
                'focus_area' => 'Biaya pendidikan & perlengkapan sekolah',
                'distribution_plan' => 'Per semester akademik',
                'summary' => 'Dukungan biaya pendidikan, buku, dan uang saku bagi pelajar berprestasi dari keluarga prasejahtera.',
                'content' => "Putus sekolah akibat keterbatasan biaya masih mengancam banyak anak berprestasi di daerah pinggiran.\n\nProgram beasiswa ini menjamin keberlanjutan sekolah mereka sekaligus memberikan pembinaan karakter secara berkala.",
                'target_amount' => 180000000,
                'current_amount' => 0,
                'donor_count' => 0,
                'start_date' => now()->subDays(110)->toDateString(),
                'end_date' => now()->addDays(50)->toDateString(),
                'publish_status' => 'published',
                'public_status' => 'active',
                'is_featured' => false,
                'display_order' => 4,
                'meta' => null,
            ],
            'ibu-hebat' => [
                'campaign_category_id' => $categories['empowerment']->id,
                'title' => 'Ruang Tumbuh Ibu Hebat Kepala Keluarga',
                'slug' => 'ibu-hebat',
                'organizer_name' => $organizer,
                'featured_badge' => 'Ibu Hebat',
                'cta_label' => 'Donasi Sekarang',
                'location' => 'Kendal & Batang',
                'focus_area' => 'Modal usaha mikro & pendampingan ibu',
                'distribution_plan' => 'Per kelompok dampingan',
                'summary' => 'Program bantuan alat usaha dan literasi keuangan bagi para ibu tunggal penopang ekonomi keluarga.',
                'content' => "Banyak ibu tunggal memikul beban pengasuhan sekaligus mencari nafkah tanpa akses permodalan yang sehat.\n\nProgram ini menghadirkan hibah peralatan usaha produktif serta pendampingan manajemen keuangan keluarga.",
                'target_amount' => 140000000,
                'current_amount' => 0,
                'donor_count' => 0,
                'start_date' => now()->subDays(75)->toDateString(),
                'end_date' => now()->addDays(5)->toDateString(), // Hampir berakhir dengan progres rendah (bahan insight AI)
                'publish_status' => 'published',
                'public_status' => 'active',
                'is_featured' => false,
                'display_order' => 5,
                'meta' => null,
            ],
            'klinik-keliling-pesisir' => [
                'campaign_category_id' => $categories['health']->id,
                'title' => 'Layanan Klinik Keliling Desa Pesisir',
                'slug' => 'klinik-keliling-pesisir',
                'organizer_name' => $organizer,
                'featured_badge' => 'Klinik Pesisir',
                'cta_label' => 'Donasi Sekarang',
                'location' => 'Pesisir Utara Jawa Tengah',
                'focus_area' => 'Pemeriksaan kesehatan & obat gratis',
                'distribution_plan' => 'Dua kali sebulan per desa',
                'summary' => 'Operasional armada kesehatan keliling untuk pemeriksaan lansia dan pencegahan stunting di desa pesisir.',
                'content' => "Warga pesisir yang terdampak rob sering kali kesulitan menjangkau fasilitas kesehatan tingkat pertama.\n\nArmada klinik keliling mendatangi langsung titik-titik pemukiman untuk memberikan pemeriksaan medis dan obat-obatan gratis.",
                'target_amount' => 160000000,
                'current_amount' => 0,
                'donor_count' => 0,
                'start_date' => now()->subDays(60)->toDateString(),
                'end_date' => now()->addDays(6)->toDateString(), // Hampir berakhir dengan progres rendah (bahan insight AI)
                'publish_status' => 'published',
                'public_status' => 'active',
                'is_featured' => false,
                'display_order' => 6,
                'meta' => null,
            ],
            'gerobak-umkm-bangkit' => [
                'campaign_category_id' => $categories['empowerment']->id,
                'title' => 'Gerobak Berkah UMKM Bangkit',
                'slug' => 'gerobak-umkm-bangkit',
                'organizer_name' => $organizer,
                'featured_badge' => 'UMKM Bangkit',
                'cta_label' => 'Donasi Sekarang',
                'location' => 'Semarang Raya',
                'focus_area' => 'Bantuan sarana usaha pedagang kecil',
                'distribution_plan' => 'Per batch 10 penerima manfaat',
                'summary' => 'Bantuan gerobak niaga baru dan modal bahan baku awal untuk pedagang kecil agar naik kelas.',
                'content' => "Sarana jualan yang layak dan bersih sangat memengaruhi kepercayaan pembeli terhadap pedagang kaki lima.\n\nProgram ini baru diluncurkan untuk membantu pedagang kecil meremajakan gerobak dan meningkatkan omzet harian mereka.",
                'target_amount' => 95000000,
                'current_amount' => 0,
                'donor_count' => 0,
                'start_date' => now()->subDays(20)->toDateString(),
                'end_date' => now()->addDays(70)->toDateString(),
                'publish_status' => 'published',
                'public_status' => 'active',
                'is_featured' => false,
                'display_order' => 7,
                'meta' => null,
            ],
            'ibu-mandiri' => [
                'campaign_category_id' => $categories['empowerment']->id,
                'title' => 'Koperasi Dapur Ibu Mandiri Berdaya',
                'slug' => 'ibu-mandiri',
                'organizer_name' => $organizer,
                'featured_badge' => 'Ibu Mandiri',
                'cta_label' => 'Lihat Detail',
                'location' => 'Salatiga & Ungaran',
                'focus_area' => 'Sentra produksi kuliner komunal',
                'distribution_plan' => 'Selesai disalurkan 100%',
                'summary' => 'Program pemberdayaan ibu melalui dapur produksi komunal yang telah tuntas dan mandiri.',
                'content' => "Program ini merupakan arsip keberhasilan campaign pemberdayaan ekonomi yang telah mencapai 100% target penghimpunan.\n\nSaat ini 35 keluarga penerima manfaat telah menjalankan usaha katering dan olahan pangan lokal secara mandiri.",
                'target_amount' => 75500000,
                'current_amount' => 0,
                'donor_count' => 0,
                'start_date' => now()->subDays(175)->toDateString(),
                'end_date' => now()->subDays(45)->toDateString(),
                'publish_status' => 'published',
                'public_status' => 'completed',
                'is_featured' => false,
                'display_order' => 8,
                'meta' => null,
            ],
            'renovasi-sekolah-dusun' => [
                'campaign_category_id' => $categories['education']->id,
                'title' => 'Renovasi Ruang Belajar Madrasah Dusun',
                'slug' => 'renovasi-sekolah-dusun',
                'organizer_name' => $organizer,
                'featured_badge' => 'Sekolah Dusun',
                'cta_label' => 'Lihat Detail',
                'location' => 'Kabupaten Temanggung',
                'focus_area' => 'Perbaikan atap, bangku & perpustakaan',
                'distribution_plan' => 'Selesai diserahterimakan',
                'summary' => 'Perbaikan ruang kelas madrasah pelosok yang sebelumnya rusak agar siswa belajar dengan aman dan nyaman.',
                'content' => "Berkat kolaborasi ratusan donatur, tiga ruang kelas dan satu pojok baca kini telah selesai direnovasi sepenuhnya.\n\nPara siswa tidak lagi khawatir saat musim hujan tiba dan memiliki koleksi buku bacaan baru yang memadai.",
                'target_amount' => 85500000,
                'current_amount' => 0,
                'donor_count' => 0,
                'start_date' => now()->subDays(165)->toDateString(),
                'end_date' => now()->subDays(20)->toDateString(),
                'publish_status' => 'published',
                'public_status' => 'completed',
                'is_featured' => false,
                'display_order' => 9,
                'meta' => null,
            ],
        ];

        $result = [];
        foreach ($definitions as $slug => $payload) {
            $result[$slug] = Campaign::query()->updateOrCreate(
                ['slug' => $payload['slug']],
                $payload
            );
        }

        return $result;
    }

    private function seedCampaignUpdates(array $campaigns): void
    {
        $updates = [
            [
                'campaign' => 'pangan-pekanan',
                'title' => 'Penyaluran 450 Paket Pangan Tahap Ke-4',
                'excerpt' => 'Relawan lapangan telah mendistribusikan paket beras dan sembako kepada lansia serta keluarga rentan di Semarang Utara.',
                'content' => 'Alhamdulillah distribusi pekan ini berjalan lancar. Sebanyak 450 paket sembako telah diterima langsung oleh keluarga prasejahtera berdasarkan data verifikasi RT/RW setempat.',
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'campaign' => 'jariyah-air',
                'title' => 'Pengeboran Titik Kedua Mencapai Kedalaman 65 Meter',
                'excerpt' => 'Tim hidrogeologi berhasil menemukan sumber mata air bersih dengan debit stabil untuk 180 KK.',
                'content' => 'Setelah proses pengeboran selama 12 hari melalui lapisan batuan kapur, debit air bersih mulai keluar dengan jernih. Tahap selanjutnya adalah pemasangan pompa submersible dan menara tandon utama.',
                'is_published' => true,
                'published_at' => now()->subDays(9),
            ],
            [
                'campaign' => 'teman-pulih',
                'title' => 'Laporan Operasional Rumah Singgah & Ambulans Bulan Lalu',
                'excerpt' => 'Sebanyak 28 pasien rujukan dari luar kota telah terlayani kebutuhan penginapan, nutrisi, dan antar-jemput rumah sakit.',
                'content' => 'Dukungan para donatur sangat meringankan beban keluarga pasien yang menjalani kemoterapi dan kontrol pasca-operasi di RSUP setempat.',
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'campaign' => 'ibu-mandiri',
                'title' => 'Serah Terima Peralatan Dapur Komunal & Pelatihan Kemasan',
                'excerpt' => 'Seluruh dana campaign telah disalurkan 100% dalam bentuk peralatan produksi standar food-grade.',
                'content' => 'Program Koperasi Dapur Ibu Mandiri resmi memasuki fase mandiri. Terima kasih kepada seluruh donatur yang telah mengantarkan program ini mencapai target penuh.',
                'is_published' => true,
                'published_at' => now()->subDays(46),
            ],
            [
                'campaign' => 'renovasi-sekolah-dusun',
                'title' => 'Peresmian Ruang Kelas Baru Bersama Warga Dusun',
                'excerpt' => 'Tiga ruang kelas, instalasi listrik, serta 60 set meja-kursi belajar baru siap digunakan siswa.',
                'content' => 'Renovasi telah selesai tepat waktu sebelum awal semester baru. Anak-anak kini memiliki ruang belajar yang terang, kokoh, dan dilengkapi pojok literasi.',
                'is_published' => true,
                'published_at' => now()->subDays(22),
            ],
        ];

        foreach ($updates as $update) {
            CampaignUpdate::query()->updateOrCreate(
                [
                    'campaign_id' => $campaigns[$update['campaign']]->id,
                    'title' => $update['title'],
                ],
                [
                    'excerpt' => $update['excerpt'],
                    'content' => $update['content'],
                    'featured_image' => null,
                    'is_published' => $update['is_published'],
                    'published_at' => $update['published_at'],
                ]
            );
        }
    }

    private function seedHeroBanners(array $campaigns): void
    {
        $banners = [
            [
                'campaign' => 'pangan-pekanan',
                'title' => 'Paket Pangan Pekanan Keluarga Rentan',
                'eyebrow' => 'Program Unggulan',
                'subtitle' => 'Jaga ketahanan pangan keluarga prasejahtera dan lansia melalui distribusi sembako rutin yang terukur.',
                'cta_label' => 'Lihat Program',
                'sort_order' => 1,
            ],
            [
                'campaign' => 'jariyah-air',
                'title' => 'Jariyah Infrastruktur Air Bersih Desa',
                'eyebrow' => 'Wakaf & Infrastruktur',
                'subtitle' => 'Bangun sumur bor dan jaringan pipa komunal untuk desa-desa yang terdampak kekeringan ekstrem.',
                'cta_label' => 'Buka Campaign',
                'sort_order' => 2,
            ],
            [
                'campaign' => 'teman-pulih',
                'title' => 'Teman Pulih Pasien Dhuafa',
                'eyebrow' => 'Kemanusiaan & Medis',
                'subtitle' => 'Dampingi perjuangan pasien dhuafa melalui layanan ambulans gratis, nutrisi, dan rumah singgah.',
                'cta_label' => 'Lihat Detail',
                'sort_order' => 3,
            ],
        ];

        foreach ($banners as $banner) {
            HeroBanner::query()->updateOrCreate(
                ['title' => $banner['title']],
                [
                    'campaign_id' => $campaigns[$banner['campaign']]->id,
                    'eyebrow' => $banner['eyebrow'],
                    'subtitle' => $banner['subtitle'],
                    'image_path' => null,
                    'cta_label' => $banner['cta_label'],
                    'cta_url' => null,
                    'is_active' => true,
                    'sort_order' => $banner['sort_order'],
                ]
            );
        }
    }

    private function seedTestimonials(): void
    {
        $items = [
            [
                'name' => 'dr. Hendra Wijaya',
                'role' => 'Donatur Rutin',
                'location' => 'Semarang',
                'quote' => 'Laporan perkembangan program di DonasiFlow Nusantara selalu jelas dan transparan. Sangat menenangkan melihat dampak nyata setiap bulan.',
                'rating' => 5,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Siti Aminah',
                'role' => 'Koordinator Relawan Desa',
                'location' => 'Grobogan',
                'quote' => 'Sejak sumur bor Jariyah Air beroperasi, warga dusun kami tidak perlu lagi berjalan jauh mencari air bersih saat musim kemarau.',
                'rating' => 5,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Rizky Pratama',
                'role' => 'Donatur Korporat',
                'location' => 'Jakarta',
                'quote' => 'Proses pencatatan dan pencarian status donasinya sangat rapi. Cocok untuk penyaluran CSR maupun donasi personal.',
                'rating' => 5,
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            Testimonial::query()->updateOrCreate(
                ['name' => $item['name'], 'quote' => $item['quote']],
                $item
            );
        }
    }

    /**
     * Membuat 70 donatur yang terbagi ke dalam 3 segmen:
     * - 8 VIP / Major Donors
     * - 18 Loyal / Repeat Donors
     * - 44 Regular / One-time Donors
     */
    private function seedDonors(): array
    {
        $firstNames = [
            'Ahmad', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fajar', 'Gita', 'Hendra', 'Indah', 'Joko',
            'Kartika', 'Lukman', 'Maya', 'Naila', 'Oki', 'Putri', 'Qori', 'Rahmawati', 'Surya', 'Taufik',
            'Ulfah', 'Vina', 'Wahyu', 'Yusuf', 'Zahra', 'Arif', 'Bagus', 'Dian', 'Farhan', 'Hana',
            'Ilham', 'Kurnia', 'Lestari', 'Mulyadi', 'Nadia', 'Prasetyo', 'Rizal', 'Salma', 'Teguh', 'Wulan',
        ];
        $lastNames = [
            'Santoso', 'Pratama', 'Wijaya', 'Saputra', 'Hidayat', 'Nugroho', 'Kusuma', 'wardhani', 'utami', 'Setiawan',
            'Permana', 'Hakim', 'Siregar', 'Nasution', 'gunawan', 'Hartono', 'Wibowo', 'Rahadian', 'Syahputra', 'fauzi',
        ];

        $segmentedDonors = [
            'vip' => [],
            'repeat' => [],
            'regular' => [],
        ];

        for ($i = 1; $i <= 70; $i++) {
            $first = $firstNames[($i - 1) % count($firstNames)];
            $last = ucfirst($lastNames[(($i * 3) + 1) % count($lastNames)]);
            $name = "{$first} {$last}";
            $email = strtolower($first . '.' . $last . $i . '@donasiflow-demo.org');
            $phone = '0812' . str_pad((string) (34000000 + ($i * 137)), 8, '0', STR_PAD_LEFT);

            $createdAt = now()->subDays(180 - min(170, $i * 2));

            $donor = Donor::query()->updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'phone' => $phone,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]
            );

            if ($i <= 8) {
                $segmentedDonors['vip'][] = $donor;
            } elseif ($i <= 26) {
                $segmentedDonors['repeat'][] = $donor;
            } else {
                $segmentedDonors['regular'][] = $donor;
            }
        }

        return $segmentedDonors;
    }

    /**
     * Membuat 400 transaksi historis selama 6 bulan terakhir (180 hari)
     * dengan pola lonjakan hari Jumat & periode gajian (tanggal 25 - 5).
     */
    private function seedHistoricalDonations(array $campaigns, array $donors, ?int $adminId): void
    {
        // Bersihkan transaksi lama agar tepat 400 baris historis yang bersih
        PaymentTransaction::query()->delete();
        Donation::query()->delete();

        $prayerMessages = [
            'Semoga menjadi amal jariyah yang terus mengalir.',
            'Bismillah, semoga membantu saudara-saudara kita yang membutuhkan.',
            'Semoga program ini berjalan lancar dan berkah untuk semua.',
            'Titip sedekah Jumat, semoga membawa keberkahan bagi keluarga kami.',
            'Semoga lekas pulih dan dimudahkan segala urusannya.',
            'Semoga adik-adik pelajar terus semangat meraih cita-cita.',
            'Alhamdulillah bisa ikut berkontribusi bulan ini, semoga bermanfaat.',
            null,
            null,
        ];

        // Bobot campaign untuk mengontrol progres masing-masing campaign:
        // - ibu-mandiri & renovasi-sekolah-dusun: completed (target penuh)
        // - pangan-pekanan & jariyah-air: tinggi (80-95%)
        // - teman-pulih & beasiswa-santri-pelosok: menengah (55-75%)
        // - ibu-hebat & klinik-keliling-pesisir: kritis/rendah (<25% menjelang end_date)
        // - gerobak-umkm-bangkit: baru dimulai (~20%)
        $campaignPools = array_merge(
            array_fill(0, 18, 'pangan-pekanan'),
            array_fill(0, 22, 'jariyah-air'),
            array_fill(0, 14, 'teman-pulih'),
            array_fill(0, 16, 'beasiswa-santri-pelosok'),
            array_fill(0, 5, 'ibu-hebat'),
            array_fill(0, 5, 'klinik-keliling-pesisir'),
            array_fill(0, 5, 'gerobak-umkm-bangkit'),
            array_fill(0, 7, 'ibu-mandiri'),
            array_fill(0, 8, 'renovasi-sekolah-dusun')
        );

        // Bangun daftar hari selama 180 hari terakhir dengan bobot:
        // - Tren naik per bulan (bulan terbaru punya bobot lebih besar)
        // - Hari Jumat: bobot 2.5x
        // - Tanggal Gajian (tgl 25-31 dan 1-5): bobot 2.2x
        $weightedDays = [];
        for ($daysAgo = 180; $daysAgo >= 1; $daysAgo--) {
            $date = now()->subDays($daysAgo);
            $weight = 10;

            // Tren pertumbuhan bulanan (semakin dekat ke hari ini, aktivitas meningkat)
            $monthRecencyBonus = (int) floor((180 - $daysAgo) / 30) * 3;
            $weight += $monthRecencyBonus;

            // Lonjakan Hari Jumat (Jumat Berkah)
            if ($date->isFriday()) {
                $weight = (int) round($weight * 2.5);
            }

            // Lonjakan Tanggal Gajian (25 s/d 5)
            $dayOfMonth = (int) $date->format('j');
            if ($dayOfMonth >= 25 || $dayOfMonth <= 5) {
                $weight = (int) round($weight * 2.2);
            }

            for ($w = 0; $w < $weight; $w++) {
                $weightedDays[] = $daysAgo;
            }
        }

        // Susun distribusi status untuk 400 transaksi:
        // 300 paid (75%), 48 expired (12%), 32 pending (8%), 16 failed (4%), 4 refunded (1%)
        $statuses = array_merge(
            array_fill(0, 300, 'paid'),
            array_fill(0, 48, 'expired'),
            array_fill(0, 32, 'pending'),
            array_fill(0, 16, 'failed'),
            array_fill(0, 4, 'refunded')
        );
        shuffle($statuses);

        $regularNominals = [25000, 50000, 75000, 100000, 150000, 200000, 250000, 300000, 500000];
        $repeatNominals = [100000, 250000, 500000, 750000, 1000000, 1500000];
        $vipNominals = [2500000, 3500000, 5000000, 7500000, 10000000];

        $manualMethods = [
            'cash' => 'Tunai (Cash)',
            'transfer' => 'Transfer Bank Langsung',
            'qris' => 'QRIS Offline',
        ];
        $manualMethodKeys = array_keys($manualMethods);

        DB::transaction(function () use (
            $statuses,
            $weightedDays,
            $campaignPools,
            $campaigns,
            $donors,
            $regularNominals,
            $repeatNominals,
            $vipNominals,
            $prayerMessages,
            $manualMethods,
            $manualMethodKeys,
            $adminId
        ) {
            for ($i = 1; $i <= 400; $i++) {
                $status = $statuses[$i - 1];
                $campaignSlug = $campaignPools[mt_rand(0, count($campaignPools) - 1)];

                // Untuk transaksi pending, pastikan hanya di campaign yang masih active dan dalam 2 hari terakhir
                if ($status === 'pending') {
                    $activeSlugs = ['pangan-pekanan', 'jariyah-air', 'teman-pulih', 'beasiswa-santri-pelosok', 'ibu-hebat', 'klinik-keliling-pesisir', 'gerobak-umkm-bangkit'];
                    $campaignSlug = $activeSlugs[mt_rand(0, count($activeSlugs) - 1)];
                    $createdAt = now()->subHours(mt_rand(1, 36))->subMinutes(mt_rand(0, 59));
                } elseif (in_array($campaignSlug, ['ibu-mandiri', 'renovasi-sekolah-dusun'], true)) {
                    // Campaign selesai: transaksi terjadi antara 30 s/d 165 hari lalu
                    $daysAgo = mt_rand(30, 165);
                    $createdAt = now()->subDays($daysAgo)->setTime(mt_rand(6, 22), mt_rand(0, 59), mt_rand(0, 59));
                } else {
                    $daysAgo = $weightedDays[mt_rand(0, count($weightedDays) - 1)];
                    $createdAt = now()->subDays($daysAgo)->setTime(mt_rand(5, 23), mt_rand(0, 59), mt_rand(0, 59));
                }

                $campaign = $campaigns[$campaignSlug];

                // Pilih segmen donatur & nominal terkalibrasi per status campaign:
                $segmentRoll = mt_rand(1, 100);
                if (in_array($campaignSlug, ['ibu-mandiri', 'renovasi-sekolah-dusun'], true)) {
                    // Campaign selesai: pastikan akumulasi mencapai >= 100% target
                    if ($segmentRoll <= 45) {
                        $donorPool = $donors['vip'];
                        $baseAmount = [3500000, 5000000, 7500000, 10000000][mt_rand(0, 3)];
                    } else {
                        $donorPool = $donors['repeat'];
                        $baseAmount = [1500000, 2000000, 2500000, 3000000][mt_rand(0, 3)];
                    }
                } elseif (in_array($campaignSlug, ['pangan-pekanan', 'jariyah-air'], true)) {
                    // Campaign unggulan hampir mencapai target (85% - 95%)
                    if ($segmentRoll <= 35) {
                        $donorPool = $donors['vip'];
                        $baseAmount = [3500000, 5000000, 7500000, 10000000][mt_rand(0, 3)];
                    } elseif ($segmentRoll <= 80) {
                        $donorPool = $donors['repeat'];
                        $baseAmount = [1000000, 1500000, 2000000, 2500000][mt_rand(0, 3)];
                    } else {
                        $donorPool = $donors['regular'];
                        $baseAmount = $regularNominals[mt_rand(0, count($regularNominals) - 1)];
                    }
                } elseif (in_array($campaignSlug, ['ibu-hebat', 'klinik-keliling-pesisir', 'gerobak-umkm-bangkit'], true)) {
                    // Campaign kritis (<25% menjelang tenggat) & campaign baru
                    if ($segmentRoll <= 50) {
                        $donorPool = $donors['repeat'];
                        $baseAmount = [500000, 750000, 1000000, 1500000][mt_rand(0, 3)];
                    } else {
                        $donorPool = $donors['regular'];
                        $baseAmount = [250000, 300000, 500000, 750000][mt_rand(0, 3)];
                    }
                } else {
                    // Campaign berjalan stabil (55% - 75%)
                    if ($segmentRoll <= 20) {
                        $donorPool = $donors['vip'];
                        $baseAmount = $vipNominals[mt_rand(0, count($vipNominals) - 1)];
                    } elseif ($segmentRoll <= 65) {
                        $donorPool = $donors['repeat'];
                        $baseAmount = $repeatNominals[mt_rand(0, count($repeatNominals) - 1)];
                    } else {
                        $donorPool = $donors['regular'];
                        $baseAmount = $regularNominals[mt_rand(0, count($regularNominals) - 1)];
                    }
                }

                /** @var Donor $donor */
                $donor = $donorPool[mt_rand(0, count($donorPool) - 1)];

                // Kanal transaksi: 20% manual_admin (hanya untuk yang berstatus paid), 80% public bank_transfer
                $isManualAdmin = ($status === 'paid' && mt_rand(1, 100) <= 20);

                if ($isManualAdmin) {
                    $uniqueCode = 0;
                    $amount = $baseAmount;
                    $reference = 'MAN-' . $createdAt->format('Ymd') . '-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT);
                    $methodKey = $manualMethodKeys[mt_rand(0, count($manualMethodKeys) - 1)];
                    $methodLabel = $manualMethods[$methodKey];
                    $gateway = 'manual_admin';
                    $paymentMethod = $methodKey;
                    $paymentBank = $methodKey === 'transfer' ? 'Bank Syariah Nasional' : null;
                    $paymentAccountNumber = $methodKey === 'transfer' ? '8899001122' : null;
                    $paymentAccountName = $methodKey === 'transfer' ? 'Yayasan DonasiFlow Nusantara' : null;
                    $metadata = [
                        'source' => 'admin_manual',
                        'payment_method' => $methodKey,
                        'payment_method_label' => $methodLabel,
                        'created_by_admin_id' => $adminId,
                        'created_by_admin_name' => 'Admin Utama',
                    ];
                    $adminNote = 'Dicatat manual melalui kasir/admin (' . $methodLabel . ')';
                } else {
                    $uniqueCode = mt_rand(11, 899);
                    $amount = $baseAmount + $uniqueCode;
                    $reference = 'INV-' . $createdAt->format('Ymd') . '-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT);
                    $gateway = 'bank_transfer';
                    $paymentMethod = 'manual_bank_transfer';
                    $paymentBank = 'Bank Syariah Nasional';
                    $paymentAccountNumber = '8899001122';
                    $paymentAccountName = 'Yayasan DonasiFlow Nusantara';
                    $metadata = [
                        'source' => 'public-bank-transfer-flow',
                    ];
                    $adminNote = $status === 'paid'
                        ? 'Mutasi sesuai kode unik, telah diverifikasi.'
                        : ($status === 'failed' ? 'Nominal transfer tidak ditemukan di mutasi.' : ($status === 'refunded' ? 'Pengembalian dana atas permintaan donatur.' : null));
                }

                $paidAt = null;
                $verifiedAt = null;
                $verifiedBy = null;
                $expiresAt = $createdAt->copy()->addDay();

                if ($status === 'paid') {
                    $paidAt = $isManualAdmin
                        ? $createdAt->copy()
                        : $createdAt->copy()->addMinutes(mt_rand(15, 360));
                    $verifiedAt = $paidAt->copy()->addMinutes(mt_rand(5, 60));
                    $verifiedBy = $adminId;
                    $expiresAt = null;
                } elseif ($status === 'refunded') {
                    $paidAt = $createdAt->copy()->addMinutes(45);
                    $verifiedAt = $paidAt->copy()->addMinutes(20);
                    $verifiedBy = $adminId;
                }

                $isAnonymous = mt_rand(1, 100) <= 28;
                $prayer = $prayerMessages[mt_rand(0, count($prayerMessages) - 1)];

                $donation = new Donation([
                    'campaign_id' => $campaign->id,
                    'donor_id' => $donor->id,
                    'reference' => $reference,
                    'donor_name' => $donor->name,
                    'donor_email' => $donor->email,
                    'donor_phone' => $donor->phone,
                    'amount' => $amount,
                    'base_amount' => $baseAmount,
                    'unique_code' => $uniqueCode,
                    'prayer_message' => $prayer,
                    'is_anonymous' => $isAnonymous,
                    'show_on_public' => true,
                    'status' => $status,
                    'paid_at' => $paidAt,
                    'expires_at' => $expiresAt,
                    'metadata' => $metadata,
                    'payment_bank' => $paymentBank,
                    'payment_account_number' => $paymentAccountNumber,
                    'payment_account_name' => $paymentAccountName,
                    'verified_at' => $verifiedAt,
                    'verified_by' => $verifiedBy,
                    'admin_note' => $adminNote,
                ]);
                $donation->created_at = $createdAt;
                $donation->updated_at = $verifiedAt ?? $createdAt;
                $donation->save();

                $transaction = new PaymentTransaction([
                    'donation_id' => $donation->id,
                    'gateway' => $gateway,
                    'external_id' => null,
                    'reference' => $reference,
                    'payment_method' => $paymentMethod,
                    'gross_amount' => $amount,
                    'fee_amount' => 0,
                    'net_amount' => $amount,
                    'status' => $status,
                    'paid_at' => $paidAt,
                    'expires_at' => $expiresAt,
                    'payload' => $isManualAdmin
                        ? [
                            'message' => 'Input manual oleh admin: Admin Utama',
                            'payment_method_label' => $metadata['payment_method_label'],
                        ]
                        : [
                            'message' => 'Manual bank transfer flow',
                            'base_amount' => $baseAmount,
                            'unique_code' => $uniqueCode,
                            'bank_account' => [
                                'bank' => 'Bank Syariah Nasional',
                                'number' => '8899001122',
                                'name' => 'Yayasan DonasiFlow Nusantara',
                            ],
                        ],
                ]);
                $transaction->created_at = $createdAt;
                $transaction->updated_at = $verifiedAt ?? $createdAt;
                $transaction->save();
            }
        });
    }
}
