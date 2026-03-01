<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\IncidentReportService;
use App\Models\IncidentReport;
use Illuminate\Support\Facades\Cache;

class IncidentReportServiceTest extends TestCase
{
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(IncidentReportService::class);
        Cache::flush();
    }

    public function test_get_total_verified_reports()
    {
        // Create test data
        IncidentReport::factory()
            ->count(5)
            ->create(['status' => IncidentReport::STATUS_VERIFIED]);

        IncidentReport::factory()
            ->count(3)
            ->create(['status' => IncidentReport::STATUS_SUBMITTED]);

        $result = $this->service->getTotalVerifiedReports();

        $this->assertEquals(5, $result);
    }

    public function test_get_today_reports()
    {
        IncidentReport::factory()
            ->count(4)
            ->create(['reported_at' => now()]);

        IncidentReport::factory()
            ->count(2)
            ->create(['reported_at' => now()->subDay()]);

        $result = $this->service->getTodayReports();

        $this->assertEquals(4, $result);
    }

    public function test_get_pending_review_reports()
    {
        IncidentReport::factory()
            ->count(3)
            ->create(['status' => IncidentReport::STATUS_SUBMITTED]);

        IncidentReport::factory()
            ->count(2)
            ->create(['status' => IncidentReport::STATUS_UNDER_REVIEW]);

        IncidentReport::factory()
            ->count(1)
            ->create(['status' => IncidentReport::STATUS_VERIFIED]);

        $result = $this->service->getPendingReviewReports();

        $this->assertEquals(5, $result);
    }

    public function test_get_casualty_stats()
    {
        IncidentReport::factory()
            ->create([
                'status' => IncidentReport::STATUS_VERIFIED,
                'casualty_deaths' => 5,
                'casualty_missing' => 3,
                'casualty_injured' => 10,
            ]);

        IncidentReport::factory()
            ->create([
                'status' => IncidentReport::STATUS_VERIFIED,
                'casualty_deaths' => 2,
                'casualty_missing' => 1,
                'casualty_injured' => 5,
            ]);

        $result = $this->service->getCasualtyStats();

        $this->assertEquals(7, $result['deaths']);
        $this->assertEquals(4, $result['missing']);
        $this->assertEquals(15, $result['injured']);
        $this->assertEquals(26, $result['total']);
    }

    public function test_cache_is_used()
    {
        IncidentReport::factory()->create(['status' => IncidentReport::STATUS_VERIFIED]);

        // First call populates cache
        $result1 = $this->service->getTotalVerifiedReports();

        // Delete all reports
        IncidentReport::query()->delete();

        // Second call returns cached value (not updated)
        $result2 = $this->service->getTotalVerifiedReports();

        $this->assertEquals($result1, $result2);
    }

    public function test_cache_invalidation()
    {
        IncidentReport::factory()->create(['status' => IncidentReport::STATUS_VERIFIED]);

        $result1 = $this->service->getTotalVerifiedReports();

        // Invalidate cache
        $this->service->invalidateKpiCache();

        // Add new report
        IncidentReport::factory()->create(['status' => IncidentReport::STATUS_VERIFIED]);

        $result2 = $this->service->getTotalVerifiedReports();

        $this->assertEquals($result1 + 1, $result2);
    }
}
