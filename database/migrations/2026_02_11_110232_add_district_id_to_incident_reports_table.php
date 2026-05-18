<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration ini dibuat no-op karena district_id tidak digunakan di schema final.
// Schema baru sudah menggunakan district_name (VARCHAR) langsung di tabel incident_reports.
return new class extends Migration
{
    public function up(): void
    {
        // No-op: digantikan oleh kolom district_name di base migration
    }

    public function down(): void
    {
        // No-op
    }
};
