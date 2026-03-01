<?php

namespace App\Livewire\Public;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class KoboIncidents extends Component
{
    public $search = '';
    public $type_filter = '';
    public $region_filter = '';
    public $page = 1;
    public $limit = 10;

    public $incidents = [];
    public $pagination = [];
    public $loading = true;
    public $error = null;

    // Reactive properties - Livewire will re-render when these change
    protected $queryString = ['page'];

    public function mount()
    {
        $this->loadIncidents();
    }

    public function updatingSearch()
    {
        $this->page = 1;
    }

    public function updatedSearch()
    {
        $this->loadIncidents();
    }

    public function updatingTypeFilter()
    {
        $this->page = 1;
    }

    public function updatedTypeFilter()
    {
        $this->loadIncidents();
    }

    public function updatingRegionFilter()
    {
        $this->page = 1;
    }

    public function updatedRegionFilter()
    {
        $this->loadIncidents();
    }

    public function updatedPage()
    {
        $this->loadIncidents();
    }

    public function loadIncidents()
    {
        $this->loading = true;
        $this->error = null;

        try {
            // Call service directly instead of HTTP
            $service = app(\App\Services\GoogleSheetsService::class);

            $filters = [];
            if ($this->type_filter) {
                $filters['type'] = $this->type_filter;
            }
            if ($this->region_filter) {
                $filters['region'] = $this->region_filter;
            }

            $result = $service->getKoboIncidents($this->page, $this->limit, $filters);

            if ($result['success']) {
                $this->incidents = $result['data'] ?? [];
                $this->pagination = $result['pagination'] ?? [];
            } else {
                $this->error = 'Gagal memuat laporan. Silakan coba lagi.';
                $this->incidents = [];
            }
        } catch (\Exception $e) {
            $this->error = 'Error: ' . $e->getMessage();
            $this->incidents = [];
            \Log::error('Kobo incidents error: ' . $e->getMessage());
        } finally {
            $this->loading = false;
        }
    }

    public function previousPage()
    {
        if ($this->page > 1) {
            $this->page--;
        }
    }

    public function nextPage()
    {
        if ($this->page < ($this->pagination['last_page'] ?? 1)) {
            $this->page++;
        }
    }

    public function render()
    {
        return view('livewire.public.kobo-incidents', [
            'incidents' => $this->incidents,
            'pagination' => $this->pagination,
            'loading' => $this->loading,
            'error' => $this->error,
            'page' => $this->page,
        ]);
    }
}

