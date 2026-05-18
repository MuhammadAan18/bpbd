<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration ini dihapus karena kolom reporter_email tidak ada
// di schema final (sudah dihapus pada base migration create_incident_reports_table).
return new class extends Migration
{
    public function up(): void
    {
        // No-op: reporter_email tidak ada di base schema baru
    }

    public function down(): void
    {
        // No-op
    }
};
