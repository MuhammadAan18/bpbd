<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\IncidentReport;

class IncidentReportApiTest extends TestCase
{
    public function test_get_dashboard_metrics_endpoint()
    {
        IncidentReport::factory()
            ->count(10)
            ->create(['status' => IncidentReport::STATUS_VERIFIED]);

        IncidentReport::factory()
            ->count(3)
            ->create(['reported_at' => now()]);

        $response = $this->getJson('/api/v1/kpi/dashboard');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'timestamp',
                'data' => [
                    'verified' => ['total', 'label', 'icon', 'color'],
                    'today' => ['total', 'label', 'icon', 'color'],
                    'pending' => ['total', 'label', 'icon', 'color'],
                    'casualty' => ['deaths', 'missing', 'injured', 'total']
                ]
            ]);
    }

    public function test_get_verified_reports_endpoint()
    {
        IncidentReport::factory(5)->create(['status' => IncidentReport::STATUS_VERIFIED]);

        $response = $this->getJson('/api/v1/kpi/verified-reports');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => ['total' => 5]
            ]);
    }

    public function test_get_today_reports_endpoint()
    {
        IncidentReport::factory(3)->create(['reported_at' => now()]);

        $response = $this->getJson('/api/v1/kpi/today-reports');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => ['total' => 3]
            ]);
    }

    public function test_get_pending_reports_endpoint()
    {
        IncidentReport::factory(2)->create(['status' => IncidentReport::STATUS_SUBMITTED]);
        IncidentReport::factory(1)->create(['status' => IncidentReport::STATUS_UNDER_REVIEW]);

        $response = $this->getJson('/api/v1/kpi/pending-reports');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => ['total' => 3]
            ]);
    }

    public function test_get_casualty_stats_endpoint()
    {
        IncidentReport::factory()
            ->create([
                'status' => IncidentReport::STATUS_VERIFIED,
                'casualty_deaths' => 5,
                'casualty_missing' => 2,
                'casualty_injured' => 8,
            ]);

        $response = $this->getJson('/api/v1/kpi/casualty-stats');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'deaths' => 5,
                    'missing' => 2,
                    'injured' => 8,
                    'total' => 15
                ]
            ]);
    }

    public function test_api_endpoints_return_valid_json()
    {
        $endpoints = [
            '/api/v1/kpi/verified-reports',
            '/api/v1/kpi/today-reports',
            '/api/v1/kpi/pending-reports',
            '/api/v1/kpi/casualty-stats',
            '/api/v1/kpi/dashboard',
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->getJson($endpoint);
            $response->assertStatus(200)
                ->assertJsonStructure(['success', 'data']);
        }
    }
}
