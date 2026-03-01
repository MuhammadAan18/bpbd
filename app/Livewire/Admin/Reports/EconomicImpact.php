<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\IncidentReport;

class EconomicImpact extends Component
{
    public IncidentReport $report;
    
    // Form fields
    public $econ_forest_affected;
    public $econ_plantation_affected;
    public $econ_rice_field_affected;
    public $econ_pond_affected;
    public $econ_factory_affected;
    public $econ_shop_affected;

    public function mount(IncidentReport $report)
    {
        $this->report = $report;
        
        // Load existing data
        $this->econ_forest_affected = $report->econ_forest_affected;
        $this->econ_plantation_affected = $report->econ_plantation_affected;
        $this->econ_rice_field_affected = $report->econ_rice_field_affected;
        $this->econ_pond_affected = $report->econ_pond_affected;
        $this->econ_factory_affected = $report->econ_factory_affected;
        $this->econ_shop_affected = $report->econ_shop_affected;
    }

    public function rules()
    {
        return [
            'econ_forest_affected' => 'nullable|integer|min:0',
            'econ_plantation_affected' => 'nullable|integer|min:0',
            'econ_rice_field_affected' => 'nullable|integer|min:0',
            'econ_pond_affected' => 'nullable|integer|min:0',
            'econ_factory_affected' => 'nullable|integer|min:0',
            'econ_shop_affected' => 'nullable|integer|min:0',
        ];
    }

    public function save()
    {
        $this->validate();

        $this->report->update([
            'econ_forest_affected' => $this->econ_forest_affected,
            'econ_plantation_affected' => $this->econ_plantation_affected,
            'econ_rice_field_affected' => $this->econ_rice_field_affected,
            'econ_pond_affected' => $this->econ_pond_affected,
            'econ_factory_affected' => $this->econ_factory_affected,
            'econ_shop_affected' => $this->econ_shop_affected,
        ]);

        session()->flash('success', 'Data Dampak Sosial Ekonomi berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.reports.economic-impact');
    }
}
