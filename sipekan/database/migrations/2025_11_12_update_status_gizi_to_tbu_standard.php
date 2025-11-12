<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Update status gizi dari standar WHO lama (gizi_buruk, kurus, normal, gemuk, obesitas)
     * ke standar baru yang menggunakan TB/U (Height-for-Age):
     * - stunting: TB/U < -2 SD
     * - normal: TB/U >= -2 SD
     */
    public function up(): void
    {
        // STEP 1: Update data lama ke standar baru SEBELUM mengubah ENUM
        // Mapping data lama ke standar baru
        // Asumsi: 'kurus', 'gizi_buruk' -> 'stunting', sisanya -> 'normal'
        DB::statement("
            UPDATE pengukurans 
            SET status_gizi = 'stunting' 
            WHERE status_gizi IN ('kurus', 'gizi_buruk')
        ");

        DB::statement("
            UPDATE pengukurans 
            SET status_gizi = 'normal' 
            WHERE status_gizi IN ('normal', 'gemuk', 'obesitas', 'kurang', 'buruk', 'lebih')
        ");

        // STEP 2: Sekarang baru update ENUM setelah data ter-update
        DB::statement("
            ALTER TABLE pengukurans 
            MODIFY COLUMN status_gizi ENUM('stunting', 'normal') DEFAULT 'normal'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke standar WHO lama
        DB::statement("
            ALTER TABLE pengukurans 
            MODIFY COLUMN status_gizi ENUM('gizi_buruk', 'kurus', 'normal', 'gemuk', 'obesitas', 'kurang', 'buruk', 'lebih') DEFAULT 'normal'
        ");

        // Mapping kembali ke data lama
        DB::statement("
            UPDATE pengukurans 
            SET status_gizi = 'kurus' 
            WHERE status_gizi = 'stunting'
        ");
    }
};
