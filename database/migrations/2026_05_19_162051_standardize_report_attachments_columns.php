<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('report_attachments', function (Blueprint $table) {
            // Drop foreign key dulu sebelum drop kolom
            if (Schema::hasColumn('report_attachments', 'id_incident_report')) {
                $table->dropForeign('report_attachments_id_incident_report_foreign');
                $table->dropColumn('id_incident_report');
            }
            if (Schema::hasColumn('report_attachments', 'file_size')) {
                $table->renameColumn('file_size', 'size');
            }
            if (!Schema::hasColumn('report_attachments', 'mime')) {
                $table->string('mime')->nullable()->after('file_path');
            }
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
            if (Schema::hasColumn('report_attachments', 'size')) {
                $table->renameColumn('size', 'file_size');
            }
            $table->dropColumn(['mime', 'original_name', 'caption']);
        });
    }
};