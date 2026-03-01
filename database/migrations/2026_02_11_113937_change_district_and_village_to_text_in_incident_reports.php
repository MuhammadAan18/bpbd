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
        Schema::table('incident_reports', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['district_id']);
            $table->dropForeign(['village_id']);
            
            // Drop columns
            $table->dropColumn('district_id');
            $table->dropColumn('village_id');

            // Add new text columns
            $table->string('district_name')->after('region_id');
            $table->string('village_name')->after('district_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incident_reports', function (Blueprint $table) {
            // Drop new text columns
            $table->dropColumn('district_name');
            $table->dropColumn('village_name');

            // Restore columns (foreign keys added in separate migrations usually, but for rollback here we just add cols)
            $table->foreignId('district_id')->nullable()->after('region_id');
            $table->foreignId('village_id')->nullable()->after('district_id');
        });
    }
};
