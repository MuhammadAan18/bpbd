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
            $table->id();
            // Tracking nomor laporan (untuk pelapor & admin)
            $table->string('report_no', 32)->unique();

            // Waktu laporan masuk vs waktu kejadian
            $table->dateTime('reported_at')->useCurrent();
            $table->dateTime('occurred_at')->nullable();

            // Relasi master data
            $table->foreignId('disaster_type_id')->constrained('disaster_types');
            $table->foreignId('region_id')->constrained('regions');

            // Lokasi
            $table->string('location_text');          // contoh: Kecamatan A, Desa B
            $table->decimal('latitude', 10, 7);        // wajib jika pilih lokasi peta
            $table->decimal('longitude', 10, 7);

            // Konten laporan
            $table->string('title')->nullable();       // opsional (bisa diisi otomatis dari jenis+lokasi)
            $table->text('description');

            // Data pelapor (tanpa login)
            $table->string('reporter_name')->nullable();
            $table->string('reporter_phone', 32)->nullable();
            $table->string('reporter_email')->nullable();

            // Status verifikasi admin
            // Nilai yang dipakai: submitted, under_review, verified, rejected
            $table->string('status', 20)->default('submitted');

            $table->dateTime('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('verification_notes')->nullable();

            $table->timestamps();

            // Index penting untuk dashboard/filter
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
