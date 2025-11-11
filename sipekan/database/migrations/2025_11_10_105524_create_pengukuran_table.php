<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengukurans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('balita_id')->constrained('balitas')->cascadeOnDelete();
            $table->foreignId('kegiatan_id')->nullable()->constrained('kegiatans')->nullOnDelete();
            $table->date('tanggal_ukur');
            $table->integer('umur_saat_ukur');
            $table->decimal('berat_badan', 5, 2);
            $table->decimal('tinggi_badan', 5, 2);
            $table->decimal('lingkar_kepala', 5, 2);
            $table->enum('status_gizi', ['normal', 'kurang', 'buruk', 'lebih'])->default('normal');
            $table->text('catatan')->nullable();
            $table->foreignId('kader_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            
            $table->index('balita_id');
            $table->index('tanggal_ukur');
            $table->index('status_gizi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengukurans');
    }
};