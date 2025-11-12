<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kegiatan;

class UpdateKegiatanStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update kegiatan ID 8 to "sedang berlangsung"
        $kegiatan = Kegiatan::find(8);
        if ($kegiatan) {
            $kegiatan->update(['status' => 'sedang berlangsung']);
            $this->command->info('Updated Kegiatan ID 8 status to "sedang berlangsung"');
        }

        // Add new kegiatan with status "sedang berlangsung"
        Kegiatan::create([
            'nama_kegiatan' => 'Pemeriksaan Kesehatan Rutin',
            'tanggal' => '2025-11-12',
            'waktu_mulai' => '09:00:00',
            'waktu_selesai' => '12:00:00',
            'lokasi' => 'Puskesmas Kecamatan A',
            'posyandu' => 'Posyandu Belwis',
            'kategori_kegiatan' => 'penimbangan',
            'status' => 'sedang berlangsung',
            'deskripsi' => 'Pemeriksaan kesehatan gratis untuk anak balita dengan fokus pada deteksi dini stunting',
            'pemateri' => 'Tim Puskesmas Kecamatan A',
            'target_peserta' => 50,
        ]);
        
        $this->command->info('Created new Kegiatan with status "sedang berlangsung"');
    }
}
