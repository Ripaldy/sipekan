<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vitamin_obats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('balita_id')->constrained('balitas')->cascadeOnDelete();
            $table->enum('jenis', ['vitamin_a', 'obat_cacing']);
            $table->date('tanggal_pemberian');
            $table->string('dosis', 50);
            $table->foreignId('kader_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            
            $table->index('balita_id');
            $table->index('jenis');
            $table->index('tanggal_pemberian');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vitamin_obats');
    }
};