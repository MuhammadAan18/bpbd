<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\IncidentReportController;

Route::prefix('v1')->name('api.')->group(function () {
    // Public KPI endpoints (no authentication required for public dashboard)
    Route::get('/kpi/verified-reports', [IncidentReportController::class, 'getVerifiedReports'])->name('kpi.verified');
    Route::get('/kpi/today-reports', [IncidentReportController::class, 'getTodayReports'])->name('kpi.today');
    Route::get('/kpi/pending-reports', [IncidentReportController::class, 'getPendingReports'])->name('kpi.pending');
    Route::get('/kpi/casualty-stats', [IncidentReportController::class, 'getCasualtyStats'])->name('kpi.casualty');

    // Aggregate KPI endpoint (single call for all metrics)
    Route::get('/kpi/dashboard', [IncidentReportController::class, 'getDashboardMetrics'])->name('kpi.dashboard');

    // Kobo incident endpoints (from Google Sheets: kobo_olah sheet)
    Route::prefix('kobo')->name('kobo.')->group(function () {
        Route::get('/incidents', [IncidentReportController::class, 'getKoboIncidents'])->name('incidents');
        Route::get('/incidents/{id}', [IncidentReportController::class, 'getKoboIncidentById'])->name('incident.show');
        Route::get('/stats', [IncidentReportController::class, 'getKoboStats'])->name('stats');
    });
});
