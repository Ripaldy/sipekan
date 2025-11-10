<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imunisasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('balita_id')->constrained('balitas')->cascadeOnDelete();
            $table->enum('jenis_vaksin', [
                'HB-0', 'BCG', 'Polio 1', 'Polio 2', 'Polio 3', 'Polio 4',
                'DPT-HB-Hib 1', 'DPT-HB-Hib 2', 'DPT-HB-Hib 3',
                'IPV', 'Campak', 'MR'
            ]);
            $table->date('tanggal_pemberian');
            $table->integer('umur_saat_imunisasi');
            $table->string('batch_number', 50)->nullable();
            $table->string('tempat_pemberian', 100);
            $table->foreignId('kader_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            
            $table->index('balita_id');
            $table->index('jenis_vaksin');
            $table->index('tanggal_pemberian');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imunisasis');
    }
};