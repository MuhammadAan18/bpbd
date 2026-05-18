<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration ini dibuat no-op karena village_id tidak digunakan di schema final.
// Schema baru menggunakan district_name (VARCHAR) untuk menyimpan informasi wilayah.
return new class extends Migration
{
    public function up(): void
    {
        // No-op: village_id tidak dibutuhkan di schema final
    }

    public function down(): void
    {
        // No-op
    }
};
