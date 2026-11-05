<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Models\Donation;
use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManualDonationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_submit_manual_donation(): void
    {
        $response = $this->post(route('admin.donations.manual.store'), [
            'amount' => '50000',
        ]);

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_submit_manual_donation_successfully(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
        ]);

        $category = CampaignCategory::query()->create([
            'name' => 'Pendidikan',
            'slug' => 'pendidikan',
            'is_active' => true,
        ]);

        $campaign = Campaign::query()->create([
            'campaign_category_id' => $category->id,
            'title' => 'Beasiswa Santri',
            'slug' => 'beasiswa-santri',
            'organizer_name' => 'Yayasan',
            'target_amount' => 10000000,
            'current_amount' => 0,
            'donor_count' => 0,
            'publish_status' => 'published',
            'public_status' => 'active',
            'start_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.donations.manual.store'), [
            'campaign_id' => $campaign->id,
            'amount' => '250.000',
            'donor_name' => 'Haji Ahmad',
            'donor_phone' => '081234567890',
            'donor_email' => 'ahmad@example.com',
            'donated_at' => now()->subDay()->toDateString(),
            'payment_method' => 'cash',
            'prayer_message' => 'Semoga berkah untuk santri.',
            'admin_note' => 'Diterima tunai di kantor sekretariat.',
            'is_anonymous' => '0',
            'show_on_public' => '1',
        ]);

        $response->assertRedirect(route('admin.donations'));
        $response->assertSessionHas('success');

        $donation = Donation::query()->first();
        $this->assertNotNull($donation);
        $this->assertSame($campaign->id, $donation->campaign_id);
        $this->assertEquals(250000, $donation->amount);
        $this->assertEquals(250000, $donation->base_amount);
        $this->assertEquals(0, $donation->unique_code);
        $this->assertSame('paid', $donation->status);
        $this->assertSame('Haji Ahmad', $donation->donor_name);
        $this->assertSame('081234567890', $donation->donor_phone);
        $this->assertSame('ahmad@example.com', $donation->donor_email);
        $this->assertSame('admin_manual', $donation->metadata['source'] ?? null);
        $this->assertSame('cash', $donation->metadata['payment_method'] ?? null);
        $this->assertSame($admin->id, $donation->verified_by);
        $this->assertNotNull($donation->paid_at);
        $this->assertNotNull($donation->verified_at);

        // Check payment transaction
        $transaction = PaymentTransaction::query()->where('donation_id', $donation->id)->first();
        $this->assertNotNull($transaction);
        $this->assertSame('paid', $transaction->status);
        $this->assertEquals(250000, $transaction->gross_amount);

        // Check campaign metric sync
        $campaign->refresh();
        $this->assertEquals(250000, $campaign->current_amount);
        $this->assertSame(1, $campaign->donor_count);
    }
}
