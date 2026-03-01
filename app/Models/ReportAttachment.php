<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportAttachment extends Model
{
    protected $fillable = [
        'incident_report_id',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'caption',
    ];

    public function incidentReport(): BelongsTo
    {
        return $this->belongsTo(IncidentReport::class, 'incident_report_id');
    }
}
