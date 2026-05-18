<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration ini dihapus karena district_id dan village_id tidak ada
// di schema baru (diganti langsung dengan district_name di base migration).
// Kolom district_name sudah ada sejak create_incident_reports_table.
return new class extends Migration
{
    public function up(): void
    {
        // No-op: district_name sudah ada di base schema, tidak perlu migrasi ini
    }

    public function down(): void
    {
        // No-op
    }
};
