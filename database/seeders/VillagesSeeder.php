<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\District;
use App\Models\Village;

class VillagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh Data Desa untuk Kecamatan di Lombok Barat
        
        // 1. Batulayar
        $batulayar = District::where('name', 'Batulayar')->first();
        if ($batulayar) {
            $villages = ['Batulayar', 'Batulayar Barat', 'Senggigi', 'Senteluk', 'Meninting', 'Sandik', 'Lembah Sari'];
            foreach ($villages as $name) {
                Village::firstOrCreate(['district_id' => $batulayar->id, 'name' => $name]);
            }
        }

        // 2. Gunungsari
        $gunungsari = District::where('name', 'Gunungsari')->first();
        if ($gunungsari) {
            $villages = ['Gunungsari', 'Jatisela', 'Kekeri', 'Mambalan', 'Mekarsari', 'Penimbung', 'Sesela', 'Taman Sari'];
             foreach ($villages as $name) {
                Village::firstOrCreate(['district_id' => $gunungsari->id, 'name' => $name]);
            }
        }

        // 3. Lingsar
        $lingsar = District::where('name', 'Lingsar')->first();
        if ($lingsar) {
            $villages = ['Lingsar', 'Batu Mekar', 'Dasamy', 'Duman', 'Gegerung', 'Karang Bayan', 'Langko'];
             foreach ($villages as $name) {
                Village::firstOrCreate(['district_id' => $lingsar->id, 'name' => $name]);
            }
        }
        
        // 4. Labuapi
        $labuapi = District::where('name', 'Labuapi')->first();
        if ($labuapi) {
            $villages = ['Labuapi', 'Bajur', 'Bagik Polak', 'Bengkel', 'Karang Bongkot', 'Kuranji', 'Merembu', 'Telagawaru'];
             foreach ($villages as $name) {
                Village::firstOrCreate(['district_id' => $labuapi->id, 'name' => $name]);
            }
        }
    }
}
