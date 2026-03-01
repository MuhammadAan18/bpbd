<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disaster_types', function (Blueprint $table) {
            // rename: disaster_name -> name
            $table->renameColumn('disaster_name', 'name');

            // tambah slug (unique)
            $table->string('slug')->unique()->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('disaster_types', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');

            $table->renameColumn('name', 'disaster_name');
        });
    }
};
