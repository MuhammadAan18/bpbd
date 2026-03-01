<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\IncidentReport;
use Illuminate\Support\Facades\Auth;
use App\Events\ReportVerified;

class Show extends Component
{
    public IncidentReport $report;
    public $verification_notes; // For rejection reason or verification notes

    public function mount(IncidentReport $report)
    {
        $this->report = $report;
        $this->verification_notes = $report->verification_notes;
    }

    public function markUnderReview()
    {
        if ($this->report->status === 'submitted') {
            $this->report->update(['status' => 'under_review']);
            session()->flash('success', 'Status diubah menjadi Diproses.');
        }
    }

    public function verify()
    {
        $this->report->update([
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => Auth::id(),
            'verification_notes' => $this->verification_notes,
        ]);

        // Dispatch event to sync to Google Sheets (runs in background via queue)
        ReportVerified::dispatch($this->report);

        session()->flash('success', 'Laporan BERHASIL diverifikasi dan dipublikasikan.');
    }

    public function reject()
    {
        $this->validate([
            'verification_notes' => 'required|string|min:5',
        ], [
            'verification_notes.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $this->report->update([
            'status' => 'rejected',
            'verified_at' => now(),
            'verified_by' => Auth::id(), // Still track who rejected
            'verification_notes' => $this->verification_notes,
        ]);

        session()->flash('success', 'Laporan DITOLAK.');
    }

    public function render()
    {
        return view('livewire.admin.reports.show');
    }
}
