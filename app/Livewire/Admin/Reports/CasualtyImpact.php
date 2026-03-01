<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\IncidentReport;

class CasualtyImpact extends Component
{
    public IncidentReport $report;
    
    // Form fields
    public $casualty_deaths;
    public $casualty_missing;
    public $casualty_injured;

    public function mount(IncidentReport $report)
    {
        $this->report = $report;
        
        // Load existing data
        $this->casualty_deaths = $report->casualty_deaths;
        $this->casualty_missing = $report->casualty_missing;
        $this->casualty_injured = $report->casualty_injured;
    }

    public function rules()
    {
        return [
            'casualty_deaths' => 'nullable|integer|min:0',
            'casualty_missing' => 'nullable|integer|min:0',
            'casualty_injured' => 'nullable|integer|min:0',
        ];
    }

    public function save()
    {
        $this->validate();

        $this->report->update([
            'casualty_deaths' => $this->casualty_deaths,
            'casualty_missing' => $this->casualty_missing,
            'casualty_injured' => $this->casualty_injured,
        ]);

        session()->flash('success', 'Data Dampak Jiwa berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.reports.casualty-impact');
    }
}
