<?php

namespace App\Livewire\Public;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\IncidentReport;
use App\Models\DisasterType;
use App\Models\Region;
use App\Services\GoogleSheetsService;
use Illuminate\Pagination\LengthAwarePaginator;

class IncidentIndex extends Component
{
    use WithPagination;

    public $search         = '';
    public $disaster_type_id = '';
    public $region_id      = '';

    protected $queryString = ['search', 'disaster_type_id', 'region_id'];

    public function updatingSearch()      { $this->resetPage(); }
    public function updatingDisasterTypeId() { $this->resetPage(); }
    public function updatingRegionId()    { $this->resetPage(); }

    /**
     * Fetch KoBO items, mapped to unified format.
     */
    private function fetchKoboItems(): \Illuminate\Support\Collection
    {
        try {
            $service = app(GoogleSheetsService::class);
            $result  = $service->getKoboIncidents(1, 1000);

            if (empty($result['success']) || empty($result['data'])) {
                return collect();
            }

            return collect($result['data'])->map(function ($kobo) {
                // apply normalization same as admin index
                if (!empty($kobo['disaster_type'])) {
                    $kobo['disaster_type'] = $this->normalizeDisasterType($kobo['disaster_type']);
                }

                return [
                    'source'       => 'kobo',
                    'data'         => $kobo,
                    'sort_date'    => $kobo['datetime_carbon'] ?? \Carbon\Carbon::createFromTimestamp(0),
                    'disaster_type'=> strtolower($kobo['disaster_type'] ?? ''),
                    'region_name'  => strtolower($kobo['region'] ?? ''),
                    'search_text'  => strtolower(implode(' ', [
                        $kobo['disaster_type'] ?? '',
                        $kobo['region'] ?? '',
                        $kobo['district'] ?? '',
                        $kobo['village'] ?? '',
                    ])),
                ];
            })->values();
        } catch (\Throwable $e) {
            \Log::error('[IncidentIndex] fetchKoboItems: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Fetch verified website reports, mapped to unified format.
     */
    private function fetchWebsiteItems(): \Illuminate\Support\Collection
    {
        $query = IncidentReport::query()
            ->with(['disasterType', 'region', 'attachments'])
            ->where('status', 'verified');

        return $query->latest('occurred_at')->get()->map(function ($r) {
            return [
                'source'       => 'website',
                'data'         => $r,
                'sort_date'    => $r->occurred_at ?? \Carbon\Carbon::createFromTimestamp(0),
                'disaster_type'=> strtolower($r->disasterType->slug ?? $r->disasterType->name ?? ''),
                'region_name'  => strtolower($r->region->name ?? ''),
                'search_text'  => strtolower(implode(' ', [
                    $r->title ?? '',
                    $r->location_text ?? '',
                    $r->disasterType->slug ?? '',
                    $r->region->name ?? '',
                ])),
            ];
        })->values();
    }

    public function render()
    {
        $disasterTypes = DisasterType::where('is_active', true)->get();
        $regions       = Region::orderBy('name')->get();

        // --- Fetch & merge ---
        $koboItems    = $this->fetchKoboItems();
        $websiteItems = $this->fetchWebsiteItems();
        $merged       = $koboItems->concat($websiteItems)
            ->sortByDesc('sort_date')
            ->values();

        // --- Filter ---
        if ($this->search) {
            $search = strtolower($this->search);
            $merged = $merged->filter(fn($i) => str_contains($i['search_text'], $search))->values();
        }

        if ($this->disaster_type_id) {
            $type = DisasterType::find($this->disaster_type_id);
            if ($type) {
                $slug = strtolower($type->slug ?? $type->name ?? '');
                $merged = $merged->filter(fn($i) => str_contains($i['disaster_type'], $slug))->values();
            }
        }

        if ($this->region_id) {
            $region = Region::find($this->region_id);
            if ($region) {
                $name = strtolower($region->name ?? '');
                $merged = $merged->filter(fn($i) => str_contains($i['region_name'], $name))->values();
            }
        }

        // --- Paginate ---
        $perPage = 9;
        $page    = $this->getPage();
        $total   = $merged->count();
        $items   = $merged->slice(($page - 1) * $perPage, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('livewire.public.incident-index', [
            'incidents'     => $paginator,
            'disaster_types'=> $disasterTypes,
            'regions'       => $regions,
        ]);
    }

    // fungsi untuk mengubah nama disaster menjadi sama
    private function normalizeDisasterType(string $raw): string
    {
        $slug = \Illuminate\Support\Str::slug($raw, '-');
        $type = \App\Models\DisasterType::where('slug', $slug)->first();
        if ($type) {
            return $type->name;
        }
        return ucwords(str_replace('_', ' ', $raw));
    }
}
