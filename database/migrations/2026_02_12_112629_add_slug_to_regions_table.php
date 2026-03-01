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
        // Add slug column to regions
        Schema::table('regions', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('name');
        });

        // Update regions with human-readable slugs
        $regionMappings = [
            'mataram' => 'Kota Mataram',
            'lombok_barat' => 'Kabupaten Lombok Barat',
            'lombok_tengah' => 'Kabupaten Lombok Tengah',
            'lombok_timur' => 'Kabupaten Lombok Timur',
            'lombok_utara' => 'Kabupaten Lombok Utara',
            'sumbawa' => 'Kabupaten Sumbawa',
            'dompu' => 'Kabupaten Dompu',
            'kota_bima' => 'Kota Bima',
            'bima' => 'Kabupaten Bima',
            'sumbawa_barat' => 'Kabupaten Sumbawa Barat',
        ];

        foreach ($regionMappings as $name => $slug) {
            DB::table('regions')
                ->where('name', $name)
                ->update(['slug' => $slug]);
        }

        // Update disaster_types slug values with readable names
        $disasterMappings = [
            'banjir' => 'Banjir',
            'longsor' => 'Tanah Longsor',
            'cuaca_ekstrem' => 'Cuaca Ekstrem',
            'gempa' => 'Gempa Bumi',
            'pasang' => 'Gelombang Pasang / Abrasi',
            'karhutla' => 'Kebakaran Hutan dan Lahan',
            'kekeringan' => 'Kekeringan',
            'erupsi' => 'Erupsi Gunung Berapi',
            'tsunami' => 'Tsunami',
        ];

        foreach ($disasterMappings as $name => $slug) {
            DB::table('disaster_types')
                ->where('name', $name)
                ->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
