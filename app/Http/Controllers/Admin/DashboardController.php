<?php

namespace App\Http\Controllers\Admin;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Donor;
use Illuminate\Contracts\View\View;

class DashboardController extends AdminController
{
    public function __invoke(): View
    {
        $totalDonations = (float) Donation::query()
            ->where('status', 'paid')
            ->sum('amount');

        $recentTransactions = Donation::query()
            ->with('campaign')
            ->latest()
            ->take(5)
            ->get();

        $endingCampaigns = Campaign::query()
            ->whereNotNull('end_date')
            ->orderBy('end_date')
            ->take(5)
            ->get()
            ->map(function (Campaign $campaign): array {
                $progress = $campaign->target_amount > 0
                    ? min(100, round(((float) $campaign->current_amount / (float) $campaign->target_amount) * 100))
                    : 0;
                $daysDifference = $campaign->end_date
                    ? (int) round(today()->diffInDays($campaign->end_date->copy()->startOfDay(), false))
                    : null;

                return [
                    'title' => $campaign->title,
                    'days_left' => $daysDifference === null
                        ? 'Tanpa batas'
                        : ($daysDifference < 0 ? abs($daysDifference) . ' hari lalu' : $daysDifference . ' hari lagi'),
                    'progress' => $progress . '%',
                ];
            });

        return view('admin.dashboard', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('dashboard'),
            'dashboard' => [
                'stats' => [
                    [
                        'label' => 'Campaign Aktif',
                        'value' => number_format(Campaign::query()->where('publish_status', 'published')->where('public_status', 'active')->count()),
                        'note' => 'Campaign publik yang masih menerima donasi',
                    ],
                    [
                        'label' => 'Donasi Masuk',
                        'value' => 'Rp ' . number_format($totalDonations, 0, ',', '.'),
                        'note' => 'Akumulasi donasi dengan status paid',
                    ],
                    [
                        'label' => 'Pending Hari Ini',
                        'value' => number_format(Donation::query()->where('status', 'pending')->whereDate('created_at', today())->count()),
                        'note' => 'Transaksi yang menunggu penyelesaian hari ini',
                    ],
                    [
                        'label' => 'Donor Tercatat',
                        'value' => number_format(Donor::query()->count()),
                        'note' => 'Data donor yang sudah tersimpan di sistem',
                    ],
                ],
                'recent_transactions' => $recentTransactions,
                'ending_campaigns' => $endingCampaigns,
            ],
        ]);
    }
}
