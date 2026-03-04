<?php

namespace App\Services;

use App\Models\IncidentReport;
use Illuminate\Support\Facades\Cache;

class IncidentReportService
{
    private const CACHE_KEY_VERIFIED = 'kpi.reports.verified';
    private const CACHE_KEY_TODAY = 'kpi.reports.today';
    private const CACHE_KEY_PENDING = 'kpi.reports.pending';
    private const CACHE_KEY_CASUALTY = 'kpi.casualty.stats';
    private const CACHE_TTL = 120; // 2 minutes - reduced from 5 minutes for faster updates

    /**
     * Get total verified incident reports with caching
     */
    public function getTotalVerifiedReports(): int
    {
        return Cache::remember(
            self::CACHE_KEY_VERIFIED,
            self::CACHE_TTL,
            fn() => IncidentReport::where('status', IncidentReport::STATUS_VERIFIED)->count()
        );
    }

    /**
     * Get today's incident reports (all statuses) with caching
     */
    public function getTodayReports(): int
    {
        return Cache::remember(
            self::CACHE_KEY_TODAY,
            self::CACHE_TTL,
            fn() => IncidentReport::whereDate('reported_at', today())->count()
        );
    }

    /**
     * Get reports pending review with caching
     */
    public function getPendingReviewReports(): int
    {
        return Cache::remember(
            self::CACHE_KEY_PENDING,
            self::CACHE_TTL,
            fn() => IncidentReport::whereIn('status', [
                IncidentReport::STATUS_SUBMITTED,
                IncidentReport::STATUS_UNDER_REVIEW
            ])->count()
        );
    }

    /**
     * Get casualty statistics (for future expansion)
     */
    public function getCasualtyStats(): array
    {
        return Cache::remember(
            self::CACHE_KEY_CASUALTY,
            self::CACHE_TTL,
            function () {
                $verified = IncidentReport::where('status', IncidentReport::STATUS_VERIFIED)->get();
                return [
                    'deaths' => $verified->sum('casualty_deaths') ?? 0,
                    'missing' => $verified->sum('casualty_missing') ?? 0,
                    'injured' => $verified->sum('casualty_injured') ?? 0,
                    'total' => ($verified->sum('casualty_deaths') ?? 0) +
                        ($verified->sum('casualty_missing') ?? 0) +
                        ($verified->sum('casualty_injured') ?? 0),
                ];
            }
        );
    }

    /**
     * Invalidate all KPI caches (call after verification/status changes)
     */
    public function invalidateKpiCache(): void
    {
        Cache::forget(self::CACHE_KEY_VERIFIED);
        Cache::forget(self::CACHE_KEY_TODAY);
        Cache::forget(self::CACHE_KEY_PENDING);
        Cache::forget(self::CACHE_KEY_CASUALTY);
    }
}
