<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\District;

class DistrictsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan Region 'Lombok Barat' ada
        $lobar = Region::firstOrCreate(['name' => 'Lombok Barat']);

        $districts = [
            'Batulayar',
            'Gunungsari',
            'Lingsar',
            'Narmada',
            'Kediri',
            'Labuapi',
            'Kuripan',
            'Gerung',
            'Lembar',
            'Sekotong',
        ];

        foreach ($districts as $name) {
            District::firstOrCreate([
                'region_id' => $lobar->id,
                'name' => $name,
            ]);
        }
    }
}
