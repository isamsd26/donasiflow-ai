<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiAnalystGuardrailTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_analyst_masks_donor_pii_sends_secret_header_and_handles_out_of_scope(): void
    {
        config()->set('services.ai_service.url', 'http://127.0.0.1:8001');
        config()->set('services.ai_service.secret', 'test-internal-secret');

        $admin = User::factory()->create();
        $category = CampaignCategory::create([
            'name' => 'Pendidikan',
            'slug' => 'pendidikan',
        ]);
        $campaign = Campaign::create([
            'campaign_category_id' => $category->id,
            'title' => 'Beasiswa Pelosok',
            'slug' => 'beasiswa-pelosok',
            'summary' => 'Ringkasan beasiswa',
            'story' => 'Cerita lengkap',
            'organizer_name' => 'Yayasan DonasiFlow',
            'target_amount' => 10000000,
            'current_amount' => 6000000,
            'public_status' => 'active',
            'is_published' => true,
        ]);

        Donation::create([
            'campaign_id' => $campaign->id,
            'reference' => 'INV-TEST-001',
            'donor_name' => 'Budi Santoso',
            'donor_email' => 'budi@example.com',
            'donor_phone' => '081234567890',
            'amount' => 6000123,
            'base_amount' => 6000000,
            'unique_code' => 123,
            'status' => 'paid',
            'is_anonymous' => false,
            'paid_at' => now(),
        ]);

        Http::fake([
            'http://127.0.0.1:8001/api/analyze-finance' => Http::response([
                'status' => 'out_of_scope',
                'guardrail_layer' => 'fast_filter',
                'message' => 'Pertanyaan berada di luar cakupan analis keuangan.',
                'telemetry' => ['latency_ms' => 0.4, 'prompt_tokens' => 0, 'output_tokens' => 0],
            ], 200),
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.ai-analyst.ask'), [
            'question' => 'bisa tanya hal random selain laporan keuangan?',
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => 'out_of_scope',
                'guardrail_layer' => 'fast_filter',
            ]);

        Http::assertSent(function ($request) {
            $body = $request->data();
            $topDonor = $body['financial_context']['donor_segmentation']['top_10_donors'][0] ?? [];

            return $request->hasHeader('X-AI-Service-Key', 'test-internal-secret')
                && ($topDonor['donor_label'] ?? null) === 'B*** S***'
                && ! array_key_exists('donor_name', $topDonor);
        });
    }
}
