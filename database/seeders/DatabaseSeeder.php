<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        if (!User::where('email', 'admin@admin.com')->exists()) {
            User::create([
                'name'              => 'Admin',
                'email'             => 'admin@admin.com',
                'password'          => Hash::make('admin'),
                'email_verified_at' => now(),
            ]);
        }

        $this->call([
            MasterDataSeeder::class,
        ]);
    }
}