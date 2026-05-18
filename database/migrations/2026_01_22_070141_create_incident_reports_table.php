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
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->increments('id');

            // Nomor kode laporan unik (contoh: REP-2026-0001)
            $table->string('report_no', 30)->unique();

            // Relasi ke pengguna (petugas/admin yang menginput)
            $table->mediumInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            // Relasi ke jenis bencana
            $table->tinyInteger('disaster_type_id')->unsigned();
            $table->foreign('disaster_type_id')->references('id')->on('disaster_types')->onDelete('cascade');

            // Relasi ke wilayah (kabupaten/kota)
            $table->tinyInteger('region_id')->unsigned();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');

            // Lokasi kejadian
            $table->string('location_text')->nullable();     // Deskripsi alamat lokasi
            $table->string('district_name', 100)->nullable(); // Nama kecamatan

            // Koordinat peta (presisi tinggi untuk GIS/WebGIS)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Judul dan kronologi/deskripsi bencana
            $table->string('title')->nullable();
            $table->text('description');                     // Kronologi kejadian bencana

            // Data pelapor
            $table->string('reporter_name', 100)->nullable();
            $table->string('reporter_phone', 20)->nullable();

            // Status laporan — dibatasi ENUM untuk menjaga integritas data
            $table->enum('status', ['pending', 'proses', 'selesai'])->default('pending');

            // Verifikasi oleh admin
            $table->dateTime('verified_at')->nullable();
            $table->mediumInteger('verified_by')->unsigned()->nullable();
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->text('verification_notes')->nullable();

            // Waktu laporan masuk & waktu kejadian bencana
            $table->dateTime('reported_at')->useCurrent();
            $table->dateTime('occurred_at')->nullable();

            $table->timestamps();

            // Index untuk performa query dashboard/filter
            $table->index(['status', 'reported_at']);
            $table->index(['status', 'occurred_at']);
            $table->index(['region_id', 'occurred_at']);
            $table->index(['disaster_type_id', 'occurred_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_reports');
    }
};
