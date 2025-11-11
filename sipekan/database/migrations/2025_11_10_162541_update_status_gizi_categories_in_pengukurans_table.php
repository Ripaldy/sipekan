<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, expand enum to include both old and new values
        DB::statement("ALTER TABLE pengukurans MODIFY COLUMN status_gizi ENUM('normal', 'kurang', 'buruk', 'lebih', 'gizi_buruk', 'kurus', 'gemuk', 'obesitas') DEFAULT 'normal'");
        
        // Update existing data to new categories
        DB::statement("UPDATE pengukurans SET status_gizi = 'gizi_buruk' WHERE status_gizi = 'buruk'");
        DB::statement("UPDATE pengukurans SET status_gizi = 'kurus' WHERE status_gizi = 'kurang'");
        DB::statement("UPDATE pengukurans SET status_gizi = 'gemuk' WHERE status_gizi = 'lebih'");
        
        // Finally, restrict enum to only new values
        DB::statement("ALTER TABLE pengukurans MODIFY COLUMN status_gizi ENUM('gizi_buruk', 'kurus', 'normal', 'gemuk', 'obesitas') DEFAULT 'normal'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to old categories
        DB::statement("UPDATE pengukurans SET status_gizi = 'buruk' WHERE status_gizi = 'gizi_buruk'");
        DB::statement("UPDATE pengukurans SET status_gizi = 'kurang' WHERE status_gizi = 'kurus'");
        DB::statement("UPDATE pengukurans SET status_gizi = 'lebih' WHERE status_gizi IN ('gemuk', 'obesitas')");
        
        DB::statement("ALTER TABLE pengukurans MODIFY COLUMN status_gizi ENUM('normal', 'kurang', 'buruk', 'lebih') DEFAULT 'normal'");
    }
};
