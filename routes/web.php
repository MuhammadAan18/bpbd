<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Public\Dashboard as PublicDashboard;
use App\Livewire\Public\IncidentIndex;
use App\Livewire\Public\KoboIncidents;
use App\Livewire\Public\ReportCreate;
use App\Livewire\Public\IncidentShow;

use App\Livewire\Admin\Reports\Index as AdminReportsIndex;
use App\Livewire\Admin\Reports\Show as AdminReportsShow;
use App\Livewire\Admin\Reports\ShowKobo;
use App\Livewire\Admin\Reports\CasualtyImpact;
use App\Livewire\Admin\Reports\HouseDamage;
use App\Livewire\Admin\Reports\InfrastructureDamage;
use App\Livewire\Admin\Reports\EconomicImpact;
use App\Livewire\Admin\Reports\BasicServicesImpact;

// public
Route::get('/', PublicDashboard::class)->name('public.dashboard');

// List kejadian
Route::get('/kejadian', IncidentIndex::class)->name('public.incidents');

// Detail kejadian dengan diagram dampak
Route::get('/kejadian/{source}/{id}', IncidentShow::class)->name('public.incidents.show');

// Kobo incidents (from Google Sheets)
Route::get('/kobo', KoboIncidents::class)->name('public.kobo');

// Form lapor bencana
Route::get('/lapor', ReportCreate::class)->name('public.report.create');

// admin
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    // Inbox laporan: submitted/under_review
    Route::get('/reports', AdminReportsIndex::class)->name('reports.index');

    // Detail KoBO data (read-only) — MUST come before {report} wildcard route
    Route::get('/reports/kobo/{kobo_id}', ShowKobo::class)->name('reports.show-kobo');

    // Detail laporan + lampiran + aksi verify/reject
    Route::get('/reports/{report}', AdminReportsShow::class)->name('reports.show');

    // Impact detail forms
    Route::get('/reports/{report}/casualty-impact', CasualtyImpact::class)->name('reports.casualty-impact');
    Route::get('/reports/{report}/house-damage', HouseDamage::class)->name('reports.house-damage');
    Route::get('/reports/{report}/infrastructure-damage', InfrastructureDamage::class)->name('reports.infrastructure-damage');
    Route::get('/reports/{report}/economic-impact', EconomicImpact::class)->name('reports.economic-impact');
    Route::get('/reports/{report}/basic-services-impact', BasicServicesImpact::class)->name('reports.basic-services-impact');

    // Jika Anda masih ingin profile dari Breeze:
    Route::view('/profile', 'profile')->name('profile');
});

require __DIR__ . '/auth.php';
