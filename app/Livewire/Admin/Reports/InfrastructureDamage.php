<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\IncidentReport;

class InfrastructureDamage extends Component
{
    public IncidentReport $report;
    
    // Form fields
    public $infra_bridge_damaged;
    public $infra_road_damaged;
    public $infra_dam_damaged;
    public $infra_embankment_damaged;
    public $infra_electricity_disrupted;
    public $infra_communication_disrupted;
    public $infra_water_damaged;
    public $infra_irrigation_damaged;

    public function mount(IncidentReport $report)
    {
        $this->report = $report;
        
        // Load existing data
        $this->infra_bridge_damaged = $report->infra_bridge_damaged;
        $this->infra_road_damaged = $report->infra_road_damaged;
        $this->infra_dam_damaged = $report->infra_dam_damaged;
        $this->infra_embankment_damaged = $report->infra_embankment_damaged;
        $this->infra_electricity_disrupted = $report->infra_electricity_disrupted;
        $this->infra_communication_disrupted = $report->infra_communication_disrupted;
        $this->infra_water_damaged = $report->infra_water_damaged;
        $this->infra_irrigation_damaged = $report->infra_irrigation_damaged;
    }

    public function rules()
    {
        return [
            'infra_bridge_damaged' => 'nullable|integer|min:0',
            'infra_road_damaged' => 'nullable|integer|min:0',
            'infra_dam_damaged' => 'nullable|integer|min:0',
            'infra_embankment_damaged' => 'nullable|integer|min:0',
            'infra_electricity_disrupted' => 'nullable|integer|min:0',
            'infra_communication_disrupted' => 'nullable|integer|min:0',
            'infra_water_damaged' => 'nullable|integer|min:0',
            'infra_irrigation_damaged' => 'nullable|integer|min:0',
        ];
    }

    public function save()
    {
        $this->validate();

        $this->report->update([
            'infra_bridge_damaged' => $this->infra_bridge_damaged,
            'infra_road_damaged' => $this->infra_road_damaged,
            'infra_dam_damaged' => $this->infra_dam_damaged,
            'infra_embankment_damaged' => $this->infra_embankment_damaged,
            'infra_electricity_disrupted' => $this->infra_electricity_disrupted,
            'infra_communication_disrupted' => $this->infra_communication_disrupted,
            'infra_water_damaged' => $this->infra_water_damaged,
            'infra_irrigation_damaged' => $this->infra_irrigation_damaged,
        ]);

        session()->flash('success', 'Data Dampak Sarpras Vital berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.reports.infrastructure-damage');
    }
}
