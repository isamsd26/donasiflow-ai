<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDonationsExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_export_donations_with_dynamic_filename_and_filter(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
        ]);

        $category = CampaignCategory::query()->create([
            'name' => 'Kemanusiaan',
            'slug' => 'kemanusiaan',
            'is_active' => true,
        ]);

        $campaign = Campaign::query()->create([
            'campaign_category_id' => $category->id,
            'title' => 'Bantu Korban Banjir',
            'slug' => 'bantu-korban-banjir',
            'organizer_name' => 'Yayasan',
            'target_amount' => 5000000,
            'current_amount' => 100000,
            'donor_count' => 1,
            'publish_status' => 'published',
            'public_status' => 'active',
            'start_date' => now()->toDateString(),
        ]);

        Donation::query()->create([
            'campaign_id' => $campaign->id,
            'reference' => 'INV-20261029-0001',
            'donor_name' => 'Ahmad Santoso',
            'amount' => 100000,
            'base_amount' => 100000,
            'unique_code' => 0,
            'status' => 'paid',
            'paid_at' => now(),
            'created_at' => now(),
        ]);

        // Test with complete date filter and status
        $response = $this->actingAs($admin)->get(route('admin.donations.export', [
            'status' => 'paid',
            'start_date' => '2026-10-01',
            'end_date' => '2026-10-29',
        ]));

        $response->assertOk();
        $this->assertTrue($response->headers->contains(
            'content-disposition',
            'attachment; filename=Laporan-Donasi_PAID_2026-10-01_sd_2026-10-29.xlsx'
        ));

        // Test without date filter
        $responseNoDate = $this->actingAs($admin)->get(route('admin.donations.export'));
        $responseNoDate->assertOk();
        $this->assertStringContainsString('Laporan-Donasi_Semua-Periode_', $responseNoDate->headers->get('content-disposition'));
    }
}
