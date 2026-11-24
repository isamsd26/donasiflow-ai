<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Models\Donation;
use App\Models\PaymentTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicDonationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_can_submit_bank_transfer_donation_and_create_pending_transaction(): void
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

        $response = $this->post(route('donation.store', $campaign->slug), [
            'amount' => '100000',
            'donor_name' => 'Rahmawati',
            'donor_email' => 'rahmawati@example.com',
            'donor_phone' => '081200000001',
            'prayer_message' => 'Semoga bermanfaat.',
            'is_anonymous' => '1',
            'payment_gateway' => 'manual',
        ]);

        $donation = Donation::query()->first();

        $response->assertRedirect(route('donation.show', $donation?->reference));
        $this->assertNotNull($donation);
        $this->assertSame($campaign->id, $donation->campaign_id);
        $this->assertSame('Rahmawati', $donation->donor_name);
        $this->assertSame('pending', $donation->status);
        $this->assertTrue($donation->is_anonymous);
        $this->assertSame('100000.00', $donation->base_amount);
        $this->assertGreaterThanOrEqual(1, $donation->unique_code);
        $this->assertLessThanOrEqual(999, $donation->unique_code);
        $this->assertSame(100000 + $donation->unique_code, (int) $donation->amount);
        $this->assertSame('Bank Syariah Nasional', $donation->payment_bank);
        $this->assertSame('8899001122', $donation->payment_account_number);
        $this->assertDatabaseHas('payment_transactions', [
            'donation_id' => $donation?->id,
            'gateway' => 'bank_transfer',
            'status' => 'pending',
        ]);
        $this->assertSame(1, PaymentTransaction::query()->count());
    }

    public function test_donation_with_special_characters_in_prayer_message_and_name_succeeds(): void
    {
        $category = CampaignCategory::query()->create([
            'name' => 'Kemanusiaan',
            'slug' => 'kemanusiaan',
            'is_active' => true,
        ]);

        $campaign = Campaign::query()->create([
            'campaign_category_id' => $category->id,
            'title' => 'Bantu Korban Bencana',
            'slug' => 'bantu-korban-bencana',
            'organizer_name' => 'Yayasan Peduli',
            'target_amount' => 10000000,
            'current_amount' => 0,
            'donor_count' => 0,
            'publish_status' => 'published',
            'public_status' => 'active',
        ]);

        $response = $this->post(route('donation.store', $campaign->slug), [
            'amount' => '50.000',
            'donor_name' => 'Hamba Allah 9-,.8l)',
            'donor_phone' => '085713616582',
            'prayer_message' => 'Semoga berkah & amanah 9-,.8l) ; -- test quotes \' " <script>',
            'payment_gateway' => 'manual',
        ]);

        $donation = Donation::query()->first();
        $this->assertNotNull($donation);
        $response->assertRedirect(route('donation.show', $donation->reference));
        $this->assertStringContainsString('9-,.8l)', $donation->prayer_message);
        $this->assertSame(50000, (int) $donation->base_amount);
    }

    public function test_donation_with_invalid_amount_returns_validation_error(): void
    {
        $category = CampaignCategory::query()->create([
            'name' => 'Sosial',
            'slug' => 'sosial',
            'is_active' => true,
        ]);

        $campaign = Campaign::query()->create([
            'campaign_category_id' => $category->id,
            'title' => 'Program Sosial',
            'slug' => 'program-sosial',
            'organizer_name' => 'Yayasan',
            'target_amount' => 5000000,
            'publish_status' => 'published',
            'public_status' => 'active',
        ]);

        // Input 9-,.8l) cleans to 98 which is below min 1000
        $response = $this->from(route('donation.create', $campaign->slug))->post(route('donation.store', $campaign->slug), [
            'amount' => '9-,.8l)',
            'donor_name' => 'Donatur',
            'donor_phone' => '081234567890',
            'payment_gateway' => 'manual',
        ]);

        $response->assertRedirect(route('donation.create', $campaign->slug));
        $response->assertSessionHasErrors('amount');
        $this->assertSame(0, Donation::query()->count());
    }

    public function test_donation_with_invalid_phone_returns_validation_error(): void
    {
        $category = CampaignCategory::query()->create([
            'name' => 'Sosial',
            'slug' => 'sosial',
            'is_active' => true,
        ]);

        $campaign = Campaign::query()->create([
            'campaign_category_id' => $category->id,
            'title' => 'Program Sosial 2',
            'slug' => 'program-sosial-2',
            'organizer_name' => 'Yayasan',
            'target_amount' => 5000000,
            'publish_status' => 'published',
            'public_status' => 'active',
        ]);

        // Phone with non-digits or too few digits
        $response = $this->from(route('donation.create', $campaign->slug))->post(route('donation.store', $campaign->slug), [
            'amount' => '50000',
            'donor_name' => 'Donatur',
            'donor_phone' => '9-,.8l)',
            'payment_gateway' => 'manual',
        ]);

        $response->assertRedirect(route('donation.create', $campaign->slug));
        $response->assertSessionHasErrors('donor_phone');
        $this->assertSame(0, Donation::query()->count());
    }
}
