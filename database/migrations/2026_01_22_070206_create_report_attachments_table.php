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
        Schema::create('report_attachments', function (Blueprint $table) {
            $table->increments('id');

            // Foreign Key ke tabel incident_reports
            $table->integer('id_incident_report')->unsigned();
            $table->foreign('id_incident_report')->references('id')->on('incident_reports')->onDelete('cascade');

            // Path/nama file di server
            $table->string('file_path', 255);

            // Ukuran file dalam Bytes (murni angka)
            $table->integer('file_size')->unsigned();

            $table->timestamps();

            $table->index('id_incident_report');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_attachments');
    }
};
