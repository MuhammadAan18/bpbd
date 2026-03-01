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
            // Dampak Sosial Ekonomi (Economic Impact)
            $table->unsignedInteger('econ_forest_affected')->nullable()->comment('Hutan terdampak (Ha)');
            $table->unsignedInteger('econ_plantation_affected')->nullable()->comment('Kebun terdampak (Ha)');
            $table->unsignedInteger('econ_rice_field_affected')->nullable()->comment('Sawah terdampak (Ha)');
            $table->unsignedInteger('econ_pond_affected')->nullable()->comment('Tambak/Kolam terdampak (Ha)');
            $table->unsignedInteger('econ_factory_affected')->nullable()->comment('Pabrik terdampak (Unit)');
            $table->unsignedInteger('econ_shop_affected')->nullable()->comment('Pertokoan/Warung terdampak (Unit)');

            // Dampak Pelayanan Dasar (Basic Services Impact)
            $table->unsignedInteger('service_office_affected')->nullable()->comment('Perkantoran terdampak (Unit)');
            $table->unsignedInteger('service_market_affected')->nullable()->comment('Pasar terdampak (Unit)');
            $table->unsignedInteger('service_education_affected')->nullable()->comment('Fasilitas pendidikan terdampak (Unit)');
            $table->unsignedInteger('service_health_affected')->nullable()->comment('Fasilitas kesehatan terdampak (Unit)');
            $table->unsignedInteger('service_worship_affected')->nullable()->comment('Fasilitas peribadatan terdampak (Unit)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incident_reports', function (Blueprint $table) {
            $table->dropColumn([
                'econ_forest_affected',
                'econ_plantation_affected',
                'econ_rice_field_affected',
                'econ_pond_affected',
                'econ_factory_affected',
                'econ_shop_affected',
                'service_office_affected',
                'service_market_affected',
                'service_education_affected',
                'service_health_affected',
                'service_worship_affected',
            ]);
        });
    }
};
