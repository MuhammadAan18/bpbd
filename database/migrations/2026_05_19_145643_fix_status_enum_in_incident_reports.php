<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE incident_reports MODIFY COLUMN status ENUM('submitted','pending','verified','rejected','in_progress','resolved') NOT NULL DEFAULT 'submitted'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE incident_reports MODIFY COLUMN status ENUM('pending','verified','rejected','in_progress','resolved') NOT NULL DEFAULT 'pending'");
    }
};