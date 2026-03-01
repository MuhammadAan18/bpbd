<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Disaster types with name (Kobo format) and slug (readable display)
        $disasterTypes = [
            ['name' => 'banjir', 'slug' => 'Banjir'],
            ['name' => 'longsor', 'slug' => 'Tanah Longsor'],
            ['name' => 'cuaca_ekstrem', 'slug' => 'Cuaca Ekstrem'],
            ['name' => 'gempa', 'slug' => 'Gempa Bumi'],
            ['name' => 'pasang', 'slug' => 'Gelombang Pasang / Abrasi'],
            ['name' => 'karhutla', 'slug' => 'Kebakaran Hutan dan Lahan'],
            ['name' => 'kekeringan', 'slug' => 'Kekeringan'],
            ['name' => 'erupsi', 'slug' => 'Erupsi Gunung Berapi'],
            ['name' => 'tsunami', 'slug' => 'Tsunami'],
        ];

        $rows = [];
        foreach ($disasterTypes as $disaster) {
            $rows[] = [
                'name' => $disaster['name'],
                'slug' => $disaster['slug'],
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('disaster_types')->upsert(
            $rows,
            ['name'],
            ['slug', 'is_active', 'updated_at']
        );

        // Regions with name (Kobo format) and slug (readable display)
        $regions = [
            ['name' => 'mataram', 'slug' => 'Kota Mataram'],
            ['name' => 'lombok_barat', 'slug' => 'Kabupaten Lombok Barat'],
            ['name' => 'lombok_tengah', 'slug' => 'Kabupaten Lombok Tengah'],
            ['name' => 'lombok_timur', 'slug' => 'Kabupaten Lombok Timur'],
            ['name' => 'lombok_utara', 'slug' => 'Kabupaten Lombok Utara'],
            ['name' => 'sumbawa', 'slug' => 'Kabupaten Sumbawa'],
            ['name' => 'dompu', 'slug' => 'Kabupaten Dompu'],
            ['name' => 'sumbawa_barat', 'slug' => 'Kabupaten Sumbawa Barat'],
            ['name' => 'kota_bima', 'slug' => 'Kota Bima'],
        ];

        $regionRows = [];
        foreach ($regions as $region) {
            $regionRows[] = [
                'name' => $region['name'],
                'slug' => $region['slug'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('regions')->upsert(
            $regionRows,
            ['name'],
            ['slug', 'updated_at']
        );
    }
}
