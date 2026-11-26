<?php

namespace App\Http\Controllers\Admin;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\PaymentTransaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AiAnalystController extends AdminController
{
    public function ask(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'min:3', 'max:1500'],
            'status' => ['nullable', Rule::in(['pending', 'paid', 'failed', 'expired', 'refunded'])],
            'q' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
        ]);

        $filters = [
            'status' => $validated['status'] ?? null,
            'q' => $validated['q'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
        ];

        $financialContext = $this->buildFinancialContext($filters);

        $aiServiceUrl = config('services.ai_service.url', 'http://127.0.0.1:8001');
        $aiServiceSecret = (string) config('services.ai_service.secret', '');
        $timeout = (int) config('services.ai_service.timeout', 45);
        @set_time_limit($timeout + 15);

        try {
            $httpClient = Http::timeout($timeout)->acceptJson();
            if ($aiServiceSecret !== '') {
                $httpClient = $httpClient->withHeaders([
                    'X-AI-Service-Key' => $aiServiceSecret,
                ]);
            }

            $response = $httpClient->post("{$aiServiceUrl}/api/analyze-finance", [
                'question' => $validated['question'],
                'filters' => (object) array_filter($filters, fn ($value) => $value !== null && $value !== ''),
                'financial_context' => $financialContext,
            ]);

            if (! $response->successful()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Layanan AI mengembalikan HTTP ' . $response->status() . ': ' . $response->body(),
                ], 502);
            }

            $payload = $response->json();

            if (($payload['status'] ?? '') === 'out_of_scope') {
                Log::info('ai_analyst.out_of_scope', [
                    'admin_id' => $request->user()?->id,
                    'guardrail_layer' => $payload['guardrail_layer'] ?? 'guardrail',
                    'telemetry' => $payload['telemetry'] ?? [],
                ]);

                return response()->json([
                    'status' => 'out_of_scope',
                    'guardrail_layer' => $payload['guardrail_layer'] ?? 'guardrail',
                    'message' => $payload['message'] ?? 'Pertanyaan berada di luar cakupan analis laporan keuangan.',
                    'telemetry' => $payload['telemetry'] ?? null,
                ]);
            }

            if (($payload['status'] ?? 'error') !== 'success') {
                return response()->json([
                    'status' => 'error',
                    'message' => $payload['message'] ?? 'Terjadi kendala saat memproses analisis AI.',
                ], 502);
            }

            Log::info('ai_analyst.success', [
                'admin_id' => $request->user()?->id,
                'model_used' => $payload['model_used'] ?? 'gemini',
                'telemetry' => $payload['telemetry'] ?? [],
            ]);

            $criticalCount = collect($financialContext['campaigns_overview'] ?? [])
                ->where('health_flag', 'kritis_mendesak_butuh_boost')
                ->count();

            return response()->json([
                'status' => 'success',
                'model_used' => $payload['model_used'] ?? 'gemini',
                'ai_response' => $payload['ai_response'] ?? '',
                'telemetry' => $payload['telemetry'] ?? null,
                'context_summary' => [
                    'filtered_total_rows' => $financialContext['filtered_scope']['total_transactions'] ?? 0,
                    'filtered_paid_tx' => $financialContext['filtered_scope']['paid_transactions'] ?? 0,
                    'filtered_paid_cash_in' => $financialContext['filtered_scope']['paid_cash_in_amount'] ?? 0,
                    'conversion_rate_pct' => $financialContext['filtered_scope']['conversion_rate_pct'] ?? 0,
                    'critical_campaigns_count' => $criticalCount,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak dapat terhubung ke microservice AI FastAPI di ' . $aiServiceUrl . '. Pastikan server uvicorn sedang berjalan. Detail: ' . $e->getMessage(),
            ], 503);
        }
    }

    private function applyDonationFilters(Builder $query, array $filters): Builder
    {
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        if (! empty($filters['q'])) {
            $keyword = trim((string) $filters['q']);
            $digits = preg_replace('/[^\d]/', '', $keyword);

            $query->where(function ($subQuery) use ($keyword, $digits): void {
                $subQuery
                    ->where('reference', 'like', "%{$keyword}%")
                    ->orWhere('donor_name', 'like', "%{$keyword}%")
                    ->orWhere('donor_email', 'like', "%{$keyword}%")
                    ->orWhere('donor_phone', 'like', "%{$keyword}%")
                    ->orWhereHas('campaign', fn ($campaignQuery) => $campaignQuery->where('title', 'like', "%{$keyword}%"));

                if ($digits !== '') {
                    $subQuery->orWhere('amount', (int) $digits);
                }
            });
        }

        return $query;
    }

    private function buildFinancialContext(array $filters): array
    {
        $filteredDonations = $this->applyDonationFilters(Donation::query()->with('campaign'), $filters)->get();

        $paidFiltered = $filteredDonations->where('status', 'paid');
        $statusBreakdown = [];
        foreach (['paid', 'pending', 'expired', 'failed', 'refunded'] as $statusKey) {
            $group = $filteredDonations->where('status', $statusKey);
            $statusBreakdown[$statusKey] = [
                'count' => $group->count(),
                'total_amount' => (int) $group->sum('amount'),
                'total_base_amount' => (int) $group->sum('base_amount'),
            ];
        }

        $totalFilteredCount = max(1, $filteredDonations->count());
        $conversionRatePct = round(($paidFiltered->count() / $totalFilteredCount) * 100, 1);

        // Monthly trend (from paid donations within filtered scope)
        $monthlyTrend = $paidFiltered
            ->groupBy(fn (Donation $d) => ($d->paid_at ?? $d->created_at)?->format('Y-m') ?? 'unknown')
            ->sortKeys()
            ->map(fn ($items, $month) => [
                'month' => $month,
                'paid_transactions' => $items->count(),
                'cash_in_amount' => (int) $items->sum('amount'),
                'base_donation_amount' => (int) $items->sum('base_amount'),
                'unique_code_surplus' => (int) $items->sum('unique_code'),
            ])
            ->values()
            ->all();

        // Day-of-week pattern & Payday (tanggal 25 - 5) analysis
        $fridayDonations = $paidFiltered->filter(fn (Donation $d) => (($d->paid_at ?? $d->created_at)?->isFriday()) === true);
        $paydayDonations = $paidFiltered->filter(function (Donation $d): bool {
            $day = (int) (($d->paid_at ?? $d->created_at)?->day ?? 15);
            return $day >= 25 || $day <= 5;
        });

        // Payment Method / Channel Breakdown from PaymentTransaction
        $filteredIds = $filteredDonations->pluck('id')->all();
        $paymentTransactions = PaymentTransaction::query()
            ->whereIn('donation_id', $filteredIds)
            ->get();

        $paymentChannelSummary = $paymentTransactions
            ->groupBy('payment_method')
            ->map(function ($rows, $method) {
                $paidRows = $rows->where('status', 'paid');
                return [
                    'payment_method' => $method ?: 'unknown',
                    'total_tx' => $rows->count(),
                    'paid_tx' => $paidRows->count(),
                    'expired_or_pending_tx' => $rows->whereIn('status', ['expired', 'pending', 'failed'])->count(),
                    'conversion_rate_pct' => $rows->count() > 0 ? round(($paidRows->count() / $rows->count()) * 100, 1) : 0,
                    'gross_paid_amount' => (int) $paidRows->sum('gross_amount'),
                    'fee_paid_amount' => (int) $paidRows->sum('fee_amount'),
                    'net_paid_amount' => (int) $paidRows->sum('net_amount'),
                ];
            })
            ->values()
            ->all();

        // Campaign performance overview (all campaigns in database)
        $campaigns = Campaign::query()->with('category')->orderBy('display_order')->get();
        $campaignPerformance = $campaigns->map(function (Campaign $c) {
            $target = max(1, (float) $c->target_amount);
            $current = (float) $c->current_amount;
            $progressPct = round(($current / $target) * 100, 1);
            $daysLeft = $c->end_date ? (int) now()->startOfDay()->diffInDays($c->end_date->startOfDay(), false) : null;

            $healthStatus = 'normal';
            if ($c->public_status === 'completed' || $progressPct >= 100) {
                $healthStatus = 'tercapai_100_persen';
            } elseif ($daysLeft !== null && $daysLeft <= 14 && $progressPct < 30) {
                $healthStatus = 'kritis_mendesak_butuh_boost';
            } elseif ($progressPct >= 80) {
                $healthStatus = 'hampir_tercapai';
            }

            return [
                'title' => $c->title,
                'slug' => $c->slug,
                'category' => $c->category?->name ?? '-',
                'public_status' => $c->public_status,
                'target_amount' => (int) $c->target_amount,
                'current_base_amount' => (int) $c->current_amount,
                'shortfall_amount' => max(0, (int) $c->target_amount - (int) $c->current_amount),
                'progress_pct' => $progressPct,
                'donor_count' => (int) $c->donor_count,
                'days_left' => $daysLeft,
                'end_date' => $c->end_date?->format('Y-m-d'),
                'health_flag' => $healthStatus,
            ];
        })->all();

        // Donor Segmentation & Retention (PII masked before leaving server to external LLM)
        $donorGrouping = $paidFiltered
            ->groupBy(fn (Donation $d) => $d->donor_email ?: ($d->donor_phone ?: $d->donor_name))
            ->map(function ($items) {
                $first = $items->first();
                return [
                    'donor_label' => $this->maskDonorName((string) ($first->donor_name ?? ''), (bool) $first->is_anonymous),
                    'is_anonymous_habit' => (bool) $first->is_anonymous,
                    'tx_count' => $items->count(),
                    'total_donated_amount' => (int) $items->sum('amount'),
                    'avg_donation_amount' => (int) round($items->avg('amount')),
                ];
            })
            ->sortByDesc('total_donated_amount')
            ->values();

        $repeatDonors = $donorGrouping->where('tx_count', '>=', 2);
        $vipDonors = $donorGrouping->where('total_donated_amount', '>=', 5000000);

        return [
            'organization' => 'Yayasan DonasiFlow Nusantara (Semarang)',
            'generated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'filtered_scope' => [
                'total_transactions' => $filteredDonations->count(),
                'paid_transactions' => $paidFiltered->count(),
                'conversion_rate_pct' => $conversionRatePct,
                'paid_cash_in_amount' => (int) $paidFiltered->sum('amount'),
                'paid_base_donation_amount' => (int) $paidFiltered->sum('base_amount'),
                'paid_unique_code_surplus' => (int) $paidFiltered->sum('unique_code'),
                'average_paid_ticket_size' => $paidFiltered->count() > 0 ? (int) round($paidFiltered->avg('amount')) : 0,
                'anonymous_paid_tx_count' => $paidFiltered->where('is_anonymous', true)->count(),
                'status_breakdown' => $statusBreakdown,
            ],
            'temporal_patterns' => [
                'monthly_trend' => $monthlyTrend,
                'friday_berkah_pattern' => [
                    'paid_tx_count' => $fridayDonations->count(),
                    'paid_amount' => (int) $fridayDonations->sum('amount'),
                    'share_of_total_paid_pct' => $paidFiltered->sum('amount') > 0
                        ? round(($fridayDonations->sum('amount') / $paidFiltered->sum('amount')) * 100, 1)
                        : 0,
                ],
                'payday_25_to_5_pattern' => [
                    'paid_tx_count' => $paydayDonations->count(),
                    'paid_amount' => (int) $paydayDonations->sum('amount'),
                    'share_of_total_paid_pct' => $paidFiltered->sum('amount') > 0
                        ? round(($paydayDonations->sum('amount') / $paidFiltered->sum('amount')) * 100, 1)
                        : 0,
                ],
            ],
            'payment_channels' => $paymentChannelSummary,
            'campaigns_overview' => $campaignPerformance,
            'donor_segmentation' => [
                'unique_paid_donors' => $donorGrouping->count(),
                'repeat_donors_count' => $repeatDonors->count(),
                'repeat_donor_retention_pct' => $donorGrouping->count() > 0
                    ? round(($repeatDonors->count() / $donorGrouping->count()) * 100, 1)
                    : 0,
                'vip_donors_count_gte_5jt' => $vipDonors->count(),
                'top_10_donors' => $donorGrouping->take(10)->values()->all(),
            ],
        ];
    }

    private function maskDonorName(string $name, bool $isAnonymous): string
    {
        $trimmed = trim($name);
        if ($isAnonymous || $trimmed === '' || strcasecmp($trimmed, 'Hamba Allah') === 0) {
            return 'Anonim';
        }

        $parts = preg_split('/\s+/', $trimmed) ?: [];

        return collect($parts)
            ->map(fn (string $word): string => mb_substr($word, 0, 1) . '***')
            ->implode(' ');
    }
}
