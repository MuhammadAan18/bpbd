<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\IncidentReport;
use App\Models\DisasterType;
use App\Services\IncidentReportService;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $totalVerified = 0;
    public $todayReports = 0;

    // Chart Data
    public $labelsType = [];
    public $dataType = [];
    public $mapIncidents = [];
    public $labelsRegion = [];
    public $dataRegion = [];

    public function mount()
    {
        // KPIs - Now using Service Layer with caching
        $reportService = app(IncidentReportService::class);
        $this->totalVerified = $reportService->getTotalVerifiedReports();
        $this->todayReports = $reportService->getTodayReports();

        // Chart: By Type (Verified Only)
        $byType = IncidentReport::where('status', 'verified')
            ->select('disaster_type_id', DB::raw('count(*) as total'))
            ->groupBy('disaster_type_id')
            ->with('disasterType')
            ->get();

        $this->labelsType = $byType->pluck('disasterType.name')->toArray();
        $this->dataType = $byType->pluck('total')->toArray();

        // Chart: By Region (Verified Only)
        $byRegion = IncidentReport::where('status', 'verified')
            ->select('region_id', DB::raw('count(*) as total'))
            ->groupBy('region_id')
            ->with('region')
            ->get();

        $this->labelsRegion = $byRegion->pluck('region.name')->toArray();
        $this->dataRegion = $byRegion->pluck('total')->toArray();

        $this->mapIncidents = IncidentReport::where('status', 'verified')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['disasterType', 'region'])
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'lat' => $item->latitude,
                    'lng' => $item->longitude,
                    'type' => $item->disasterType->name ?? '-',
                    'region' => $item->region->name ?? '-',
                    'location' => $item->location_text,
                ];
            })->toArray();
    }

    /**
     * Refresh KPI data (call from JavaScript after data updates)
     * This can be triggered by a button or scheduled polling
     */
    public function refreshKpis()
    {
        $this->mount();
    }

    public function render()
    {
        return view('livewire.public.dashboard', [
            'recentReports' => IncidentReport::where('status', 'verified')
                ->latest('occurred_at')
                ->take(5)
                ->with(['disasterType', 'region'])
                ->get(),

            'mapIncidents' => IncidentReport::where('status', 'verified')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get()
                ->map(fn($i) => [
                    'lat' => $i->latitude,
                    'lng' => $i->longitude,
                    'title' => $i->title,
                    'type' => $i->disasterType->name,
                    'region' => $i->region->name,
                    'location' => $i->location_text,
                ])
        ]);
    }
}
