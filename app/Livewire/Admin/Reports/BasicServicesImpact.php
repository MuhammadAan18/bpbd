<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\IncidentReport;

class BasicServicesImpact extends Component
{
    public IncidentReport $report;
    
    // Form fields
    public $service_office_affected;
    public $service_market_affected;
    public $service_education_affected;
    public $service_health_affected;
    public $service_worship_affected;

    public function mount(IncidentReport $report)
    {
        $this->report = $report;
        
        // Load existing data
        $this->service_office_affected = $report->service_office_affected;
        $this->service_market_affected = $report->service_market_affected;
        $this->service_education_affected = $report->service_education_affected;
        $this->service_health_affected = $report->service_health_affected;
        $this->service_worship_affected = $report->service_worship_affected;
    }

    public function rules()
    {
        return [
            'service_office_affected' => 'nullable|integer|min:0',
            'service_market_affected' => 'nullable|integer|min:0',
            'service_education_affected' => 'nullable|integer|min:0',
            'service_health_affected' => 'nullable|integer|min:0',
            'service_worship_affected' => 'nullable|integer|min:0',
        ];
    }

    public function save()
    {
        $this->validate();

        $this->report->update([
            'service_office_affected' => $this->service_office_affected,
            'service_market_affected' => $this->service_market_affected,
            'service_education_affected' => $this->service_education_affected,
            'service_health_affected' => $this->service_health_affected,
            'service_worship_affected' => $this->service_worship_affected,
        ]);

        session()->flash('success', 'Data Dampak Pelayanan Dasar berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.reports.basic-services-impact');
    }
}
