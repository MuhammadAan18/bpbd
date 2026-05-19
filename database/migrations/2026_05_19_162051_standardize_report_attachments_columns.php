<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('report_attachments', function (Blueprint $table) {
            // Rename kolom lama
            $table->renameColumn('id_incident_report', 'incident_report_id');
            $table->renameColumn('file_size', 'size');
            // Tambah kolom yang kurang
            $table->string('mime')->nullable()->after('file_path');
            $table->string('original_name')->nullable()->after('mime');
            $table->string('caption')->nullable()->after('original_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
