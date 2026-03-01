<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use App\Services\GoogleSheetsService;

class ShowKobo extends Component
{
    public $kobo_id;
    public $kobo_data = [];
    public $loading = false;
    public $error = null;
    public $temporary_notes = '';

    public function mount($kobo_id)
    {
        $this->kobo_id = $kobo_id;
        $this->loadKoboData();
    }

    private function loadKoboData()
    {
        $this->loading = true;
        $this->error = null;

        try {
            $service = app(GoogleSheetsService::class);
            $result = $service->getKoboIncidents(1, 100);

            if ($result['success']) {
                // Extract index from kobo_id (format: kobo_NUMBER)
                $index = (int) str_replace('kobo_', '', $this->kobo_id);

                if (isset($result['data'][$index])) {
                    $this->kobo_data = $result['data'][$index];
                } else {
                    $this->error = 'Data KoBO tidak ditemukan.';
                }
            } else {
                $this->error = 'Gagal memuat data KoBO.';
            }
        } catch (\Exception $e) {
            $this->error = 'Error: ' . $e->getMessage();
            \Log::error('Error loading KoBO data: ' . $e->getMessage());
        } finally {
            $this->loading = false;
        }
    }

    public function goBack()
    {
        return redirect()->route('admin.reports.index', ['status' => 'verified']);
    }

    public function render()
    {
        return view('livewire.admin.reports.show-kobo');
    }
}
