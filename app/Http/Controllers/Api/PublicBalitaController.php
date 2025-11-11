<?php
// app/Http/Controllers/Api/PublicBalitaController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Balita;
use Illuminate\Http\Request;

class PublicBalitaController extends Controller
{
    /**
     * Search balita by kode_anak (public access)
     * Tidak perlu authentication untuk endpoint ini
     */
    public function searchByCode(Request $request)
    {
        $request->validate([
            'kode_anak' => 'required|string|min:3'
        ]);

        $kode = strtoupper(trim($request->kode_anak));
        
        $balita = Balita::where('id_balita', $kode)
            ->with(['pengukurans' => function($query) {
                $query->orderBy('tanggal_ukur', 'desc')->limit(10);
            }])
            ->first();

        if (!$balita) {
            return response()->json([
                'success' => false,
                'message' => 'Data anak tidak ditemukan. Pastikan kode yang dimasukkan benar.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data anak ditemukan',
            'data' => [
                'kode_anak' => $balita->id_balita,
                'nama_lengkap' => $balita->nama,
                'tanggal_lahir' => $balita->tanggal_lahir->format('Y-m-d'),
                'jenis_kelamin' => $balita->jenis_kelamin,
                'usia_bulan' => $balita->umur_sekarang,
                'nama_orang_tua' => $balita->nama_orang_tua,
                'no_telepon_orang_tua' => $balita->no_telepon_ortu,
                'alamat' => $balita->alamat,
                'riwayat_pengukuran' => $balita->pengukurans->map(function($p) {
                    return [
                        'tanggal' => $p->tanggal_ukur,
                        'usia_bulan' => $p->usia_bulan,
                        'berat_badan' => $p->berat_badan,
                        'tinggi_badan' => $p->tinggi_badan,
                        'lingkar_kepala' => $p->lingkar_kepala,
                        'status_gizi' => $p->status_gizi,
                    ];
                })
            ]
        ]);
    }

    /**
     * Get statistics for home page
     */
    public function getStatistics()
    {
        $totalBalita = Balita::count();
        $balitaSehat = Balita::whereHas('pengukurans', function($query) {
            $query->where('status_gizi', 'Normal')
                  ->whereRaw('tanggal_ukur = (SELECT MAX(tanggal_ukur) FROM pengukurans WHERE balita_id = balitas.id)');
        })->count();

        $persentaseSehat = $totalBalita > 0 
            ? round(($balitaSehat / $totalBalita) * 100) 
            : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'total_balita' => $totalBalita,
                'balita_sehat' => $balitaSehat,
                'persentase_sehat' => $persentaseSehat,
                'siap_melayani' => true
            ]
        ]);
    }
}