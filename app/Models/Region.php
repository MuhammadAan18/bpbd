<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\District;
use App\Models\IncidentReport;

class Region extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function incidentReports()
    {
        return $this->hasMany(IncidentReport::class);
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
