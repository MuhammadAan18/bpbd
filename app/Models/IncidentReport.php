<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\IncidentReportService;

class IncidentReport extends Model
{
    use HasFactory;
    // Status constants (hindari typo di berbagai tempat)
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_VERIFIED = 'verified';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'report_no',
        'reported_at',
        'occurred_at',
        'disaster_type_id',
        'region_id',
        'location_text',
        'latitude',
        'longitude',
        'title',
        'description',
        'reporter_name',
        'reporter_phone',
        'status',
        'verified_at',
        'verified_by',
        'verification_notes',
        // Impact details
        'casualty_deaths',
        'casualty_missing',
        'casualty_injured',
        'house_heavy_damage',
        'house_moderate_damage',
        'house_light_damage',
        'house_flooded',
        'infra_bridge_damaged',
        'infra_road_damaged',
        'infra_dam_damaged',
        'infra_embankment_damaged',
        'infra_electricity_disrupted',
        'infra_communication_disrupted',
        'infra_water_damaged',
        'infra_irrigation_damaged',
        'district_name',
        'village_name',
        // Economic impact
        'econ_forest_affected',
        'econ_plantation_affected',
        'econ_rice_field_affected',
        'econ_pond_affected',
        'econ_factory_affected',
        'econ_shop_affected',

        // Public Service
        'service_office_affected',
        'service_market_affected',
        'service_education_affected',
        'service_health_affected',
        'service_worship_affected',

        // Process status
        'status', // submitted, under_review, verified, rejected
        'verified_at',
        'verified_by',
        'verification_notes',
        'synced_to_sheets_at',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'occurred_at' => 'datetime',
        'verified_at' => 'datetime',
        'synced_to_sheets_at' => 'datetime',
    ];

    // Relationships
    public function disasterType(): BelongsTo
    {
        return $this->belongsTo(DisasterType::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    // Lampiran
    public function attachments(): HasMany
    {
        return $this->hasMany(ReportAttachment::class);
    }

    // Admin verifikator (User dari Breeze)
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes (untuk query yang sering dipakai)
    public function scopePublicVerified($query)
    {
        return $query->where('status', self::STATUS_VERIFIED);
    }

    public function scopePendingReview($query)
    {
        return $query->whereIn('status', [self::STATUS_SUBMITTED, self::STATUS_UNDER_REVIEW]);
    }

    // Helper: tanggal yang dipakai untuk analitik (fallback)
    public function getEventAtAttribute()
    {
        return $this->occurred_at ?? $this->reported_at;
    }

    protected static function booted(): void
    {
        // Invalidate KPI cache whenever report is created/updated/deleted
        static::created(function () {
            app(IncidentReportService::class)->invalidateKpiCache();
        });

        static::updated(function () {
            app(IncidentReportService::class)->invalidateKpiCache();
        });

        static::deleted(function () {
            app(IncidentReportService::class)->invalidateKpiCache();
        });
    }
}
