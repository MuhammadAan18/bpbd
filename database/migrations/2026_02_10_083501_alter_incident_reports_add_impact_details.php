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
            // Dampak Jiwa (Casualties)
            $table->unsignedInteger('casualty_deaths')->nullable()->comment('Korban meninggal dunia');
            $table->unsignedInteger('casualty_missing')->nullable()->comment('Korban hilang');
            $table->unsignedInteger('casualty_injured')->nullable()->comment('Korban luka-luka');

            // Dampak Kerusakan Rumah (House Damage)
            $table->unsignedInteger('house_heavy_damage')->nullable()->comment('Rumah rusak berat');
            $table->unsignedInteger('house_moderate_damage')->nullable()->comment('Rumah rusak sedang');
            $table->unsignedInteger('house_light_damage')->nullable()->comment('Rumah rusak ringan');
            $table->unsignedInteger('house_flooded')->nullable()->comment('Rumah terendam');

            // Dampak Sarpras Vital (Infrastructure)
            $table->unsignedInteger('infra_bridge_damaged')->nullable()->comment('Jembatan rusak (unit)');
            $table->unsignedInteger('infra_road_damaged')->nullable()->comment('Jalan rusak (meter)');
            $table->unsignedInteger('infra_dam_damaged')->nullable()->comment('Bendungan rusak (unit)');
            $table->unsignedInteger('infra_embankment_damaged')->nullable()->comment('Tanggul rusak (meter)');
            $table->unsignedInteger('infra_electricity_disrupted')->nullable()->comment('Jaringan listrik tergganggu (unit/titik)');
            $table->unsignedInteger('infra_communication_disrupted')->nullable()->comment('Jaringan komunikasi terganggu (unit/titik)');
            $table->unsignedInteger('infra_water_damaged')->nullable()->comment('Jaringan air bersih rusak (meter)');
            $table->unsignedInteger('infra_irrigation_damaged')->nullable()->comment('Jaringan irigasi rusak (meter)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incident_reports', function (Blueprint $table) {
            $table->dropColumn([
                'casualty_deaths',
                'casualty_missing',
                'casualty_injured',
                'house_heavy_damage',
                'house_moderate_damage',
                'house_light_damage',
                'house_flooded',
                'infra_bridge_damaged',
                'infra_road_damaged',
                'infra_dam_damaged',
                'infra_embankment_damaged',
                'infra_electricity_disrupted',
                'infra_communication_disrupted',
                'infra_water_damaged',
                'infra_irrigation_damaged',
            ]);
        });
    }
};
