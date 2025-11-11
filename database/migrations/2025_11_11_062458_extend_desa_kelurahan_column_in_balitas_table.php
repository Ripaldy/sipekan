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
        Schema::table('balitas', function (Blueprint $table) {
            // Extend desa_kelurahan column from 20 to 100 characters
            $table->string('desa_kelurahan', 100)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('balitas', function (Blueprint $table) {
            // Revert back to 20 characters
            $table->string('desa_kelurahan', 20)->change();
        });
    }
};
