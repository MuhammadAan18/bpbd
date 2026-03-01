<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\IncidentReport;

class HouseDamage extends Component
{
    public IncidentReport $report;
    
    // Form fields
    public $house_heavy_damage;
    public $house_moderate_damage;
    public $house_light_damage;
    public $house_flooded;

    public function mount(IncidentReport $report)
    {
        $this->report = $report;
        
        // Load existing data
        $this->house_heavy_damage = $report->house_heavy_damage;
        $this->house_moderate_damage = $report->house_moderate_damage;
        $this->house_light_damage = $report->house_light_damage;
        $this->house_flooded = $report->house_flooded;
    }

    public function rules()
    {
        return [
            'house_heavy_damage' => 'nullable|integer|min:0',
            'house_moderate_damage' => 'nullable|integer|min:0',
            'house_light_damage' => 'nullable|integer|min:0',
            'house_flooded' => 'nullable|integer|min:0',
        ];
    }

    public function save()
    {
        $this->validate();

        $this->report->update([
            'house_heavy_damage' => $this->house_heavy_damage,
            'house_moderate_damage' => $this->house_moderate_damage,
            'house_light_damage' => $this->house_light_damage,
            'house_flooded' => $this->house_flooded,
        ]);

        session()->flash('success', 'Data Kerusakan Rumah berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.reports.house-damage');
    }
}
