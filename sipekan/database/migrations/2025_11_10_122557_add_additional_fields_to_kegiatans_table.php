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
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->string('posyandu', 100)->nullable()->after('id');
            $table->enum('status', ['terjadwal', 'selesai', 'dibatalkan'])->default('terjadwal')->after('kategori_kegiatan');
            $table->string('pemateri', 100)->nullable()->after('deskripsi');
            $table->integer('target_peserta')->nullable()->after('pemateri');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropColumn(['posyandu', 'status', 'pemateri', 'target_peserta']);
        });
    }
};
