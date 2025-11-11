<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan', 100);
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('lokasi', 100);
            $table->enum('kategori_kegiatan', ['imunisasi', 'penimbangan', 'penyuluhan', 'posyandu']);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            
            $table->index('tanggal');
            $table->index('kategori_kegiatan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};