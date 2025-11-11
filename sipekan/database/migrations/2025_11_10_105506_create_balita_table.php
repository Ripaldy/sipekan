<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('balitas', function (Blueprint $table) {
            $table->id();
            $table->string('id_balita', 50)->unique();
            $table->string('nama', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir');
            $table->string('nik_balita', 16)->nullable();
            $table->string('nama_orang_tua', 100);
            $table->string('nik_orang_tua', 16);
            $table->text('alamat');
            $table->string('rt_rw', 20);
            $table->string('no_telepon_ortu', 20);
            $table->string('foto_balita')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->index('id_balita');
            $table->index('nama');
            $table->index('tanggal_lahir');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('balitas');
    }
};