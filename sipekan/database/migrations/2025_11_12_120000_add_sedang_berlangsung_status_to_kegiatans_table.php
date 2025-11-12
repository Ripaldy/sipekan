<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations - Add 'sedang berlangsung' status to kegiatans table
     */
    public function up(): void
    {
        // Change enum to include 'sedang berlangsung'
        Schema::table('kegiatans', function (Blueprint $table) {
            // For MySQL, we need to modify the column
            // Drop old enum and create new one with additional value
            $table->enum('status', ['terjadwal', 'sedang berlangsung', 'selesai', 'dibatalkan'])
                  ->default('terjadwal')
                  ->change();
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            // Revert to old enum without 'sedang berlangsung'
            $table->enum('status', ['terjadwal', 'selesai', 'dibatalkan'])
                  ->default('terjadwal')
                  ->change();
        });
    }
};
