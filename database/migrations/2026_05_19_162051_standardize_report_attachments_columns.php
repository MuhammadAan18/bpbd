<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('report_attachments', function (Blueprint $table) {
            // Hapus file_size karena size sudah ada
            if (Schema::hasColumn('report_attachments', 'file_size')) {
                $table->dropColumn('file_size');
            }
            // Tambah kolom yang belum ada
            if (!Schema::hasColumn('report_attachments', 'original_name')) {
                $table->string('original_name')->nullable()->after('mime');
            }
            if (!Schema::hasColumn('report_attachments', 'caption')) {
                $table->string('caption')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('report_attachments', function (Blueprint $table) {
            $table->dropColumn(['original_name', 'caption']);
        });
    }
};