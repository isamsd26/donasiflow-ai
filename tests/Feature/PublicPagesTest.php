<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Models\StaticPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Cek Status Donasi');
    }

    public function test_published_campaign_detail_page_loads(): void
    {
        $category = CampaignCategory::query()->create([
            'name' => 'Humanity',
            'slug' => 'humanity',
            'description' => 'Program kemanusiaan',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $campaign = Campaign::query()->create([
            'campaign_category_id' => $category->id,
            'title' => 'Teman Pulih',
            'slug' => 'teman-pulih',
            'organizer_name' => 'Yayasan Peduli Harapan',
            'featured_badge' => 'Teman Pulih',
            'cta_label' => 'Donasi Sekarang',
            'summary' => 'Ringkasan campaign teman pulih.',
            'content' => 'Konten campaign teman pulih.',
            'target_amount' => 5000000,
            'current_amount' => 1000000,
            'donor_count' => 10,
            'start_date' => now()->subDays(1)->toDateString(),
            'end_date' => now()->addDays(20)->toDateString(),
            'publish_status' => 'published',
            'public_status' => 'active',
            'is_featured' => false,
            'display_order' => 1,
        ]);

        StaticPage::query()->create([
            'slug' => 'contact',
            'title' => 'Kontak',
            'intro' => 'Kontak singkat',
            'content' => 'Konten kontak',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get(route('campaign.show', $campaign->slug));

        $response->assertOk();
        $response->assertSee($campaign->title);
    }
}
