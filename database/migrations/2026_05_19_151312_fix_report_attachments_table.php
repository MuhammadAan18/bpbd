<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('report_attachments', function (Blueprint $table) {
            if (!Schema::hasColumn('report_attachments', 'incident_report_id')) {
                $table->unsignedBigInteger('incident_report_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('report_attachments', 'mime')) {
                $table->string('mime')->nullable()->after('file_path');
            }
            if (!Schema::hasColumn('report_attachments', 'size')) {
                $table->unsignedBigInteger('size')->nullable()->after('mime');
            }
        });
    }

    public function down(): void
    {
        Schema::table('report_attachments', function (Blueprint $table) {
            $table->dropColumn(['incident_report_id', 'mime', 'size']);
        });
    }
};