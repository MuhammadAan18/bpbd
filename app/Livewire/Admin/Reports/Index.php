<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\IncidentReport;
use App\Services\GoogleSheetsService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    use WithPagination;

    public $status = 'submitted';
    public $search = '';
    public $koboCount = 0;

    protected $queryString = ['status'];

    public function setStatus($status)
    {
        $this->status = $status;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Fetch KoBO data from Google Sheets.
     * Returns a Collection of unified-format items, or empty collection on failure.
     */
    private function fetchKoboItems(): \Illuminate\Support\Collection
    {
        try {
            $service = app(GoogleSheetsService::class);
            $result = $service->getKoboIncidents(1, 1000);

            if (empty($result['success']) || empty($result['data'])) {
                return collect();
            }

            return collect($result['data'])->map(function ($kobo, $index) {
                // normalize disaster_type to match website database
                if (!empty($kobo['disaster_type'])) {
                    $kobo['disaster_type'] = $this->normalizeDisasterType($kobo['disaster_type']);
                }

                // Use datetime_carbon built by parseKoboRow() for accurate sorting.
                // It is a Carbon object parsed from the "d/m/Y H:i:s" datetime_raw.
                $createdAt = $kobo['datetime_carbon'] ?? null;

                // Fallback: try parsing just the date part (less accurate, but better than now())
                if (!$createdAt && !empty($kobo['date'])) {
                    try {
                        $createdAt = \Carbon\Carbon::createFromFormat('d/m/Y', $kobo['date']);
                    } catch (\Throwable $e) {
                        $createdAt = null;
                    }
                }

                return [
                    'source' => 'kobo',
                    'data' => $kobo,
                    'id' => 'kobo_' . $index,
                    'created_at' => $createdAt ?? \Carbon\Carbon::createFromTimestamp(0), // oldest if unknown
                ];
            });
        } catch (\Throwable $e) {
            Log::error('[Index] fetchKoboItems failed: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Convert a raw disaster type string from Kobo into the canonical name used on the website.
     * Tries a lookup by slug first, falls back to humanizing the snake case.
     */
    private function normalizeDisasterType(string $raw): string
    {
        $slug = \Illuminate\Support\Str::slug($raw, '-');
        $type = \App\Models\DisasterType::where('slug', $slug)->first();
        if ($type) {
            return $type->name;
        }

        // Fallback: replace underscores with spaces and capitalize words
        return ucwords(str_replace('_', ' ', $raw));
    }

    /**
     * Fetch website reports for a given status, wrapped in unified format.
     */
    private function fetchWebsiteItems(?string $status = null): \Illuminate\Support\Collection
    {
        $query = IncidentReport::query()->with(['disasterType', 'region']);

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('report_no', 'like', '%' . $this->search . '%')
                    ->orWhere('title', 'like', '%' . $this->search . '%')
                    ->orWhere('location_text', 'like', '%' . $this->search . '%');
            });
        }

        return $query->latest('reported_at')->get()->map(function ($r) {
            return [
                'source' => 'website',
                'data' => $r,
                'id' => 'web_' . $r->id,
                'created_at' => $r->verified_at ?? $r->reported_at,
            ];
        })->values();
    }

    /**
     * Build a LengthAwarePaginator from a Collection.
     */
    private function paginateCollection(\Illuminate\Support\Collection $items, int $perPage = 10): LengthAwarePaginator
    {
        // Use Livewire's current page (from WithPagination trait)
        $page = $this->getPage();
        $total = $items->count();

        $slice = $items->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $slice,
            $total,
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }

    private function getCounts(): array
    {
        $koboCount = 0;
        try {
            $service = app(GoogleSheetsService::class);
            $result = $service->getKoboIncidents(1, 1);
            $koboCount = (int) ($result['pagination']['total'] ?? 0);
        } catch (\Throwable $e) {
            Log::error('[Index] getCounts kobo failed: ' . $e->getMessage());
        }

        $websiteVerified = IncidentReport::where('status', 'verified')->count();

        return [
            'submitted' => IncidentReport::where('status', 'submitted')->count(),
            'under_review' => IncidentReport::where('status', 'under_review')->count(),
            'verified' => $websiteVerified + $koboCount,
            'rejected' => IncidentReport::where('status', 'rejected')->count(),
            // 'all'          => IncidentReport::count(),
        ];
    }

    public function render()
    {
        if ($this->status === 'verified') {
            // Merge website verified + KoBO
            $websiteItems = $this->fetchWebsiteItems('verified');
            $koboItems = $this->fetchKoboItems();

            // Apply search filter for KoBO items
            if ($this->search) {
                $koboItems = $koboItems->filter(function ($item) {
                    return str_contains(
                        strtolower($item['data']['id'] ?? ''),
                        strtolower($this->search)
                    ) || str_contains(
                        strtolower($item['data']['disaster_type'] ?? ''),
                        strtolower($this->search)
                    );
                })->values();
            }

            // Merge and sort newest first
            $merged = $websiteItems->concat($koboItems)
                ->sortByDesc('created_at')
                ->values();

            $reports = $this->paginateCollection($merged);
        } else {
            $items = $this->fetchWebsiteItems($this->status);
            $reports = $this->paginateCollection($items);
        }

        return view('livewire.admin.reports.index', [
            'reports' => $reports,
            'counts' => $this->getCounts(),
        ]);
    }
}
