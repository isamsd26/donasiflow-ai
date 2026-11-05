<?php

namespace App\Support;

use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Models\CampaignUpdate;
use App\Models\Donation;
use App\Models\FaqItem;
use App\Models\HeroBanner;
use App\Models\Setting;
use App\Models\StaticPage;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PublicSite
{
    public static function site(?string $title = null, ?string $description = null): array
    {
        $siteName = self::settingValue('site_name', 'DonasiFlow Nusantara');
        $tagline = self::settingValue('site_tagline', 'Campaign stories for real-world impact');

        return [
            'name' => $siteName,
            'title' => $title ? "{$title} - {$siteName}" : $siteName,
            'description' => $description ?? 'Platform donasi berbasis campaign dengan fokus pada pengalaman publik yang jelas, hangat, dan mudah digunakan.',
            'tagline' => $tagline,
        ];
    }

    public static function navigation(): array
    {
        $items = [
            ['label' => 'Beranda', 'route' => 'home'],
            ['label' => 'Campaign', 'route' => 'campaign.index'],
            ['label' => 'Cek Status', 'route' => 'donation.lookup'],
        ];

        foreach (['about' => 'Tentang', 'contact' => 'Kontak', 'faq' => 'FAQ'] as $slug => $label) {
            if (StaticPage::query()->where('slug', $slug)->where('is_published', true)->exists()) {
                $items[] = ['label' => $label, 'route' => 'pages.show', 'params' => ['slug' => $slug]];
            }
        }

        return $items;
    }

    public static function heroSlides(): array
    {
        $banners = HeroBanner::query()
            ->with('campaign')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($banners->isNotEmpty()) {
            return $banners->map(function (HeroBanner $banner): array {
                return [
                    'eyebrow' => $banner->eyebrow ?: 'Campaign unggulan',
                    'headline' => $banner->title,
                    'description' => $banner->subtitle ?: ($banner->campaign?->summary ?: 'Lihat campaign unggulan yang dikelola melalui CMS admin.'),
                    'cta' => $banner->cta_label ?: 'Lihat Program',
                    'url' => $banner->cta_url ?: ($banner->campaign ? route('campaign.show', $banner->campaign->slug) : route('campaign.index')),
                    'image_url' => self::mediaUrl($banner->image_path),
                ];
            })->all();
        }

        return [
            [
                'eyebrow' => 'Campaign unggulan',
                'headline' => 'Ruang Aman Ibu Tangguh',
                'description' => 'Program pendampingan untuk ibu dan keluarga rentan agar memiliki ruang tumbuh yang lebih aman, sehat, dan berdaya.',
                'cta' => 'Lihat Program',
                'url' => route('campaign.index'),
                'image_url' => null,
            ],
        ];
    }

    public static function filters(): array
    {
        $filters = collect([
            ['label' => 'All', 'value' => 'all'],
            ['label' => 'Donasi Aktif', 'value' => 'active'],
            ['label' => 'Donasi Selesai', 'value' => 'finished'],
        ]);

        CampaignCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->each(function (CampaignCategory $category) use ($filters): void {
                $filters->push([
                    'label' => $category->name,
                    'value' => $category->slug,
                ]);
            });

        return $filters->all();
    }

    public static function socials(): array
    {
        return [
            ['label' => 'Facebook', 'icon' => 'facebook', 'url' => self::settingValue('social_facebook', 'https://facebook.com/donasiflow')],
            ['label' => 'Instagram', 'icon' => 'instagram', 'url' => self::settingValue('social_instagram', 'https://instagram.com/donasiflow')],
            ['label' => 'Hubungi Kami', 'icon' => 'whatsapp', 'url' => route('pages.show', ['slug' => 'contact'])],
        ];
    }

    public static function footer(): array
    {
        return [
            'description' => self::settingValue('footer_description', 'Platform donasi berbasis campaign dengan pengelolaan konten yang rapi, transparan, dan mudah diperbarui dari CMS admin.'),
            'copyright' => self::settingValue('footer_copyright', 'Copyright ' . now()->year . ' DonasiFlow Nusantara. Semua hak dilindungi.'),
            'cta' => [
                'label' => self::settingValue('footer_primary_cta_label', 'Cek Status Donasi'),
                'url' => self::settingValue('footer_primary_cta_url', route('donation.lookup')),
            ],
            'contact' => [
                'email' => self::settingValue('contact_email', 'halo@donasiflow.org'),
                'whatsapp' => self::settingValue('whatsapp', '081200001234'),
                'address' => self::settingValue('contact_address', 'Jl. Pemuda No. 88, Kec. Semarang Tengah, Kota Semarang, Jawa Tengah 50132'),
                'hours' => self::settingValue('contact_hours', 'Senin - Jumat, 09.00 - 17.00 WIB'),
            ],
            'navigation' => self::menuSetting('footer_navigation_menu', [
                ['label' => 'Campaign', 'url' => route('campaign.index')],
                ['label' => 'Tentang', 'url' => route('pages.show', ['slug' => 'about'])],
                ['label' => 'Kontak', 'url' => route('pages.show', ['slug' => 'contact'])],
            ]),
            'support' => self::menuSetting('footer_support_menu', [
                ['label' => 'FAQ', 'url' => route('pages.show', ['slug' => 'faq'])],
                ['label' => 'Tentang', 'url' => route('pages.show', ['slug' => 'about'])],
                ['label' => 'Kebijakan Privasi', 'url' => route('pages.show', ['slug' => 'privacy-policy'])],
            ]),
        ];
    }

    public static function impact(): array
    {
        $totalBeneficiaries = self::settingValue('impact_total_beneficiaries');

        return [
            'value' => $totalBeneficiaries ?: number_format((int) Campaign::query()->sum('donor_count')),
            'label' => 'lebih penerima manfaat',
        ];
    }

    public static function faqItems(): array
    {
        return FaqItem::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (FaqItem $item) => [
                'question' => $item->question,
                'answer' => $item->answer,
            ])->all();
    }

    public static function testimonials(): array
    {
        return Testimonial::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (Testimonial $item) => [
                'name' => $item->name,
                'role' => $item->role,
                'location' => $item->location,
                'quote' => $item->quote,
                'rating' => max(1, min(5, (int) $item->rating)),
            ])->all();
    }

    public static function campaigns(): Collection
    {
        $campaigns = self::publicCampaignQuery()
            ->with('category')
            ->orderByDesc('is_featured')
            ->orderBy('display_order')
            ->latest('id')
            ->get();

        if ($campaigns->isEmpty()) {
            return collect();
        }

        return $campaigns->values()->map(fn (Campaign $campaign, int $index) => self::mapCampaign($campaign, $index));
    }

    public static function campaign(string $slug): ?array
    {
        $campaign = self::publicCampaignQuery()
            ->with(['category', 'updates' => fn ($query) => $query->latest('published_at')->latest('id')])
            ->where('slug', $slug)
            ->first();

        if (! $campaign) {
            return null;
        }

        return self::mapCampaign($campaign, 0, true);
    }

    public static function relatedCampaigns(string $slug, int $limit = 3): Collection
    {
        $current = self::publicCampaignQuery()->where('slug', $slug)->first();

        if (! $current) {
            return collect();
        }

        return self::publicCampaignQuery()
            ->with('category')
            ->whereKeyNot($current->id)
            ->where('campaign_category_id', $current->campaign_category_id)
            ->orderByDesc('is_featured')
            ->orderBy('display_order')
            ->take($limit)
            ->get()
            ->values()
            ->map(fn (Campaign $campaign, int $index) => self::mapCampaign($campaign, $index));
    }

    public static function latestDonations(?string $slug = null): array
    {
        $query = Donation::query()
            ->where('status', 'paid')
            ->where('show_on_public', true)
            ->with('campaign')
            ->latest('paid_at')
            ->latest('id');

        if ($slug) {
            $campaign = Campaign::query()->where('slug', $slug)->first();

            if (! $campaign) {
                return [];
            }

            $query->where('campaign_id', $campaign->id);
        }

        return $query->take(5)->get()->map(function (Donation $donation): array {
            return [
                'name' => $donation->is_anonymous ? 'Anonim' : $donation->donor_name,
                'campaign' => $donation->campaign?->title ?? '-',
                'amount' => 'Rp ' . number_format((float) $donation->amount, 0, ',', '.'),
                'date' => optional($donation->paid_at ?? $donation->created_at)?->diffForHumans(),
                'status' => str($donation->status)->headline()->toString(),
            ];
        })->all();
    }

    public static function prayerMessages(string $slug, int $limit = 10): array
    {
        $campaign = Campaign::query()->where('slug', $slug)->first();

        if (! $campaign) {
            return [];
        }

        return Donation::query()
            ->where('campaign_id', $campaign->id)
            ->where('status', 'paid')
            ->where('show_on_public', true)
            ->whereNotNull('prayer_message')
            ->latest('paid_at')
            ->latest('id')
            ->take($limit)
            ->get()
            ->map(function (Donation $donation): array {
                return [
                    'name' => $donation->is_anonymous ? 'Anonim' : $donation->donor_name,
                    'amount' => 'Rp ' . number_format((float) $donation->amount, 0, ',', '.'),
                    'message' => $donation->prayer_message,
                    'date' => optional($donation->paid_at ?? $donation->created_at)?->translatedFormat('d F Y'),
                ];
            })->all();
    }

    public static function page(string $slug): ?array
    {
        $page = StaticPage::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->first();

        if (! $page) {
            return null;
        }

        return [
            'title' => $page->title,
            'eyebrow' => 'Halaman Statis',
            'intro' => $page->intro ?: Str::limit(strip_tags((string) $page->content), 180),
            'content' => $page->content,
            'seo_title' => $page->seo_title,
            'seo_description' => $page->seo_description,
            'slug' => $page->slug,
            'faq_items' => $page->slug === 'faq' ? self::faqItems() : [],
            'contact_cards' => $page->slug === 'contact'
                ? [
                    ['label' => 'Email', 'value' => self::settingValue('contact_email', 'halo@donasiflow.org')],
                    ['label' => 'WhatsApp', 'value' => self::settingValue('whatsapp', '081200001234')],
                    ['label' => 'Alamat', 'value' => self::settingValue('contact_address', 'Jl. Pemuda No. 88, Kec. Semarang Tengah, Kota Semarang, Jawa Tengah 50132')],
                    ['label' => 'Jam Layanan', 'value' => self::settingValue('contact_hours', 'Senin - Jumat, 09.00 - 17.00 WIB')],
                ]
                : [],
        ];
    }

    private static function mapCampaign(Campaign $campaign, int $index = 0, bool $withDetail = false): array
    {
        $targetAmount = (float) $campaign->target_amount;
        $currentAmount = (float) $campaign->current_amount;
        $progress = $targetAmount > 0 ? (int) min(100, round(($currentAmount / $targetAmount) * 100)) : 0;
        $status = $campaign->public_status === 'completed' ? 'finished' : ($campaign->public_status === 'hidden' ? 'hidden' : 'active');
        $daysLeft = self::daysLeft($campaign);
        $meta = collect([
            'Lokasi' => $campaign->location,
            'Fokus' => $campaign->focus_area,
            'Penyaluran' => $campaign->distribution_plan,
        ])
            ->filter(fn ($value) => filled($value))
            ->all();

        if (empty($meta)) {
            $meta = collect($campaign->meta ?? [])
                ->filter(fn ($value, $key) => filled($value) && is_string($key))
                ->all();
        }

        $story = collect(preg_split("/\r\n|\n|\r/", trim((string) $campaign->content)))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();

        $updates = $withDetail
            ? $campaign->updates->map(fn (CampaignUpdate $update) => [
                'title' => $update->title,
                'date' => optional($update->published_at ?? $update->created_at)?->translatedFormat('d F Y'),
                'body' => $update->content ?: ($update->excerpt ?: 'Belum ada isi update.'),
            ])->all()
            : [];

        $donors = $withDetail
            ? Donation::query()
                ->where('campaign_id', $campaign->id)
                ->where('status', 'paid')
                ->where('show_on_public', true)
                ->latest('paid_at')
                ->take(8)
                ->get()
                ->map(fn (Donation $donation) => [
                    'name' => $donation->is_anonymous ? 'Anonim' : $donation->donor_name,
                    'amount' => 'Rp ' . number_format((float) $donation->amount, 0, ',', '.'),
                    'date' => optional($donation->paid_at ?? $donation->created_at)?->translatedFormat('d F Y'),
                ])->all()
            : [];

        return [
            'slug' => $campaign->slug,
            'title' => $campaign->title,
            'poster_title' => $campaign->featured_badge ?: Str::limit($campaign->title, 18, ''),
            'badge' => $campaign->category?->slug ?? 'campaign',
            'organizer' => $campaign->organizer_name,
            'collected' => 'Rp ' . number_format($currentAmount, 0, ',', '.'),
            'target' => 'Rp ' . number_format($targetAmount, 0, ',', '.'),
            'progress' => $progress,
            'days_left' => $daysLeft,
            'days_left_short' => $daysLeft,
            'status' => $status,
            'status_label' => $status === 'finished' ? 'Selesai' : ($status === 'hidden' ? 'Hidden' : 'Aktif'),
            'category' => $campaign->category?->slug ?? 'campaign',
            'cta' => $status === 'finished' ? 'Lihat Detail' : ($campaign->cta_label ?: 'Donasi Sekarang'),
            'cover' => self::coverStyle($campaign, $index),
            'summary' => $campaign->summary ?: Str::limit(strip_tags((string) $campaign->content), 140),
            'story' => $story,
            'meta' => $meta,
            'donor_count' => number_format((int) $campaign->donor_count),
            'share_links' => [
                ['label' => 'Facebook', 'url' => '#'],
                ['label' => 'WhatsApp', 'url' => '#'],
                ['label' => 'Twitter/X', 'url' => '#'],
                ['label' => 'Copy URL', 'url' => '#'],
            ],
            'updates' => $updates,
            'donors' => $donors,
        ];
    }

    private static function daysLeft(Campaign $campaign): string
    {
        if (! $campaign->end_date) {
            return 'Tanpa batas';
        }

        $daysDifference = (int) round(today()->diffInDays($campaign->end_date->copy()->startOfDay(), false));

        if ($daysDifference < 0) {
            return abs($daysDifference) . ' hari lalu';
        }

        return $daysDifference . ' hari lagi';
    }

    private static function coverStyle(Campaign $campaign, int $index): string
    {
        if ($campaign->featured_image) {
            return "url('" . self::mediaUrl($campaign->featured_image) . "') center/cover";
        }

        $gradients = [
            'linear-gradient(135deg, #059669, #1e293b)',
            'linear-gradient(135deg, #047857, #064e3b)',
            'linear-gradient(135deg, #1e293b, #064e3b)',
            'linear-gradient(135deg, #059669, #065f46)',
        ];

        return $gradients[$index % count($gradients)];
    }

    private static function settingValue(string $key, ?string $default = null): ?string
    {
        $setting = Setting::query()->where('key', $key)->first();

        if (! $setting) {
            return $default;
        }

        return $setting->value['value'] ?? $default;
    }

    private static function publicCampaignQuery()
    {
        return Campaign::query()
            ->where('publish_status', 'published')
            ->whereIn('public_status', ['active', 'completed']);
    }

    private static function mediaUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return Storage::url($path);
    }

    private static function menuSetting(string $key, array $default): array
    {
        $setting = Setting::query()->where('key', $key)->first();
        $value = $setting?->value;

        if (! is_array($value)) {
            return $default;
        }

        $items = collect($value)
            ->map(function ($item) {
                if (! is_array($item)) {
                    return null;
                }

                $label = trim((string) ($item['label'] ?? ''));
                $url = trim((string) ($item['url'] ?? ''));

                if ($label === '' || $url === '') {
                    return null;
                }

                return [
                    'label' => $label,
                    'url' => $url,
                ];
            })
            ->filter()
            ->values()
            ->all();

        return $items !== [] ? $items : $default;
    }
}
