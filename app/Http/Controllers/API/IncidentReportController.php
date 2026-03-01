<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\IncidentReportService;
use App\Services\GoogleSheetsService;
use Illuminate\Http\JsonResponse;

class IncidentReportController extends Controller
{
    public function __construct(
        private IncidentReportService $reportService,
        private GoogleSheetsService $sheetsService
    )
    {
    }

    /**
     * Get total verified incident reports
     */
    public function getVerifiedReports(): JsonResponse  
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total' => $this->reportService->getTotalVerifiedReports(),
                'label' => 'Total Terverifikasi',
                'icon' => 'check-circle',
                'color' => 'blue'
            ]
        ]);
    }

    /**
     * Get today's incident reports
     */
    public function getTodayReports(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total' => $this->reportService->getTodayReports(),
                'label' => 'Laporan Hari Ini',
                'icon' => 'clock',
                'color' => 'orange'
            ]
        ]);
    }

    /**
     * Get pending review reports (for admin dashboard future use)
     */
    public function getPendingReports(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total' => $this->reportService->getPendingReviewReports(),
                'label' => 'Menunggu Review',
                'icon' => 'hourglass',
                'color' => 'yellow'
            ]
        ]);
    }

    /**
     * Get casualty statistics
     */
    public function getCasualtyStats(): JsonResponse
    {
        $stats = $this->reportService->getCasualtyStats();

        return response()->json([
            'success' => true,
            'data' => [
                'deaths' => $stats['deaths'],
                'missing' => $stats['missing'],
                'injured' => $stats['injured'],
                'total' => $stats['total']
            ]
        ]);
    }

    /**
     * Aggregate endpoint: Get all dashboard metrics from Google Sheets (Looker combined data)
     * This endpoint fetches data from Total_Data sheet which already combines Kobo + Looker data
     * Optimized for frontend to reduce number of requests
     */
    public function getDashboardMetrics(): JsonResponse
    {
        // Get KPI data from Google Sheets (Looker combined data)
        $sheetsData = $this->sheetsService->getKpiData();

        return response()->json([
            'success' => $sheetsData['success'] ?? true,
            'timestamp' => now()->toIso8601String(),
            'data' => [
                'incident' => $sheetsData['data']['incident'] ?? [
                    'total' => 0,
                    'label' => 'Jumlah Kejadian',
                    'icon' => 'alert-triangle',
                    'color' => 'blue'
                ],
                'affected_people' => $sheetsData['data']['affected_people'] ?? [
                    'total' => 0,
                    'label' => 'Jiwa Terdampak',
                    'icon' => 'users',
                    'color' => 'orange'
                ],
                'damaged_houses' => $sheetsData['data']['damaged_houses'] ?? [
                    'total' => 0,
                    'label' => 'Total Rumah Terdampak',
                    'icon' => 'home',
                    'color' => 'yellow'
                ]
            ]
        ]);
    }

    /**
     * Get paginated list of Kobo incidents from Google Sheets
     */
    public function getKoboIncidents(): JsonResponse
    {
        $page = request('page', 1);
        $limit = request('limit', 10);
        $filters = [
            'type' => request('type'),
            'region' => request('region'),
            'status' => request('status'),
        ];

        $data = $this->sheetsService->getKoboIncidents($page, $limit, $filters);

        return response()->json([
            'success' => true,
            'data' => $data['incidents'],
            'pagination' => $data['pagination'],
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Get single Kobo incident by ID
     */
    public function getKoboIncidentById(string $id): JsonResponse
    {
        $incident = $this->sheetsService->getKoboIncidentById($id);

        if (!$incident) {
            return response()->json([
                'success' => false,
                'message' => 'Incident not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $incident,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Get Kobo incidents summary statistics
     */
    public function getKoboStats(): JsonResponse
    {
        $stats = $this->sheetsService->getKoboStats();

        return response()->json([
            'success' => true,
            'data' => $stats,
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
