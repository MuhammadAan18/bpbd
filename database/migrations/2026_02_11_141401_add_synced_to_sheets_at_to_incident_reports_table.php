<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration ini dibuat no-op karena kolom verification_notes tidak ada
// di schema final (dihapus dari base migration create_incident_reports_table).
return new class extends Migration
{
    public function up(): void
    {
        // No-op: verification_notes tidak ada di schema baru,
        // synced_to_sheets_at tidak dibutuhkan di schema inti laporan
    }

    public function down(): void
    {
        // No-op
    }
};
