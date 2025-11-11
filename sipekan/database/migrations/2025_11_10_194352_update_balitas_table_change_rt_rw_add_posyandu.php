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
            // Rename rt_rw to desa_kelurahan
            $table->renameColumn('rt_rw', 'desa_kelurahan');
            
            // Add posyandu field after desa_kelurahan
            $table->string('posyandu', 100)->after('desa_kelurahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('balitas', function (Blueprint $table) {
            // Remove posyandu field
            $table->dropColumn('posyandu');
            
            // Rename back desa_kelurahan to rt_rw
            $table->renameColumn('desa_kelurahan', 'rt_rw');
        });
    }
};
