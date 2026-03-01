<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Delete all old records
        DB::table('regions')->truncate();
        DB::table('disaster_types')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = now();

        // Insert new disaster types with snake_case format
        DB::table('disaster_types')->insert([
            ['name' => 'banjir', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'longsor', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'cuaca_ekstrem', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'gempa', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'pasang', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'karhutla', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'kekeringan', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'erupsi', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'tsunami', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Insert new regions with snake_case format
        DB::table('regions')->insert([
            ['name' => 'mataram', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'lombok_barat', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'lombok_tengah', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'lombok_timur', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'lombok_utara', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'sumbawa', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'dompu', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'bima', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'sumbawa_barat', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete all data when rolling back
        DB::table('regions')->truncate();
        DB::table('disaster_types')->truncate();
    }
};
