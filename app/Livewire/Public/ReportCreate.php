<?php

namespace App\Livewire\Public;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\DisasterType;
use App\Models\Region;
use App\Models\IncidentReport;
use App\Models\ReportAttachment;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ReportCreate extends Component
{
    use WithFileUploads;

    // Form inputs
    public $occurred_at;
    public $disaster_type_id;
    public $region_id;
    public $district_name;
    public $village_name;
    public $location_text;
    public $latitude;
    public $longitude;
    public $title;
    public $description;

    // Reporter Identity (Optional but recommended)
    public $reporter_name;
    public $reporter_phone;


    // Uploads
    public $photo;

    // Master Data
    public $disaster_types = [];
    public $regions = [];

    // UI States
    public $isSubmitted = false;
    public $submittedReportNo = null;

    public function mount()
    {
        $this->disaster_types = DisasterType::where('is_active', true)->get();
        $this->regions = Region::orderBy('name')->get();
        // Default time to now
        $this->occurred_at = now()->format('Y-m-d\TH:i');
    }

    public function rules()
    {
        return [
            'disaster_type_id' => 'required|exists:disaster_types,id',
            'region_id' => 'required|exists:regions,id',
            'district_name' => 'required|string|max:255',
            'village_name' => 'required|string|max:255',
            // Allow 1 hour buffer into future to handle client/server clock skew
            'occurred_at' => 'required|date|before_or_equal:' . now()->addHour()->format('Y-m-d H:i:s'),
            'location_text' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'reporter_name' => 'required|string|max:100',
            'reporter_phone' => 'required|string|max:32',

            'photo' => 'nullable|image|max:10240', // 10MB max
        ];
    }

    public function messages()
    {
        return [
            'latitude.required' => 'Lokasi pada peta wajib dipilih.',
            'photo.image' => 'File bukti harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 10MB.',
        ];
    }

    public function save()
    {
        // standard Livewire validation; errors are automatically sent to the frontend
        $this->validate();

        // Generate Report No: REP-YYMMDD-RANDOM
        $reportNo = 'REP-' . date('ymd') . '-' . strtoupper(Str::random(5));

        while (IncidentReport::where('report_no', $reportNo)->exists()) {
            $reportNo = 'REP-' . date('ymd') . '-' . strtoupper(Str::random(5));
        }

        $report = IncidentReport::create([
            'report_no' => $reportNo,
            'occurred_at' => $this->occurred_at,
            'disaster_type_id' => $this->disaster_type_id,
            'region_id' => $this->region_id,
            'district_name' => $this->district_name,
            'village_name' => $this->village_name,
            'location_text' => $this->location_text,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'title' => $this->title ?? 'Laporan ' . DisasterType::find($this->disaster_type_id)->name . ' di ' . $this->location_text, // Auto title if empty
            'description' => $this->description,
            'reporter_name' => $this->reporter_name,
            'reporter_phone' => $this->reporter_phone,

            'status' => 'submitted',
        ]);

        // Handle Photo Upload
        if ($this->photo) {
            $path = $this->photo->store('incident-attachments', 'public');

            ReportAttachment::create([
                'incident_report_id' => $report->id,
                'file_path' => $path,
                'mime' => $this->photo->getMimeType(),
                'size' => $this->photo->getSize(),
                'original_name' => $this->photo->getClientOriginalName(),
            ]);
        }

        $this->submittedReportNo = $reportNo;
        $this->isSubmitted = true;

        $this->reset(['photo', 'description', 'location_text', 'latitude', 'longitude', 'title']);
    }

    public function render()
    {
        return view('livewire.public.report-create');
    }
}
