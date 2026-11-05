<?php

namespace App\Support;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Support\Facades\Schema;

class CampaignMetrics
{
    public static function sync(Campaign $campaign): void
    {
        $paidDonations = Donation::query()
            ->where('campaign_id', $campaign->id)
            ->where('status', 'paid');

        $amountColumn = Schema::hasColumn('donations', 'base_amount') ? 'base_amount' : 'amount';

        $campaign->update([
            'current_amount' => (float) $paidDonations->sum($amountColumn),
            'donor_count' => (int) $paidDonations->count(),
        ]);
    }
}
