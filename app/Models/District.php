<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = ['region_id', 'name'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function incidentReports()
    {
        return $this->hasMany(IncidentReport::class);
    }

    public function villages()
    {
        return $this->hasMany(Village::class);
    }
}
