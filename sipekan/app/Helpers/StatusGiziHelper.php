<?php

namespace App\Helpers;

/**
 * Helper untuk menghitung status gizi berdasarkan standar terbaru
 * 
 * Standar:
 * - Anak Stunting: TB/U < -2 SD (tinggi badan pendek menurut umur akibat kekurangan gizi kronis)
 * - Anak Normal: TB/U >= -2 SD (pertumbuhan sesuai usia dan status gizi baik)
 */
class StatusGiziHelper
{
    /**
     * Menentukan status gizi berdasarkan tinggi badan menurut umur (TB/U)
     * 
     * @param float $tbuScore Skor TB/U (dalam SD - Standard Deviation)
     * @return string 'stunting' atau 'normal'
     */
    public static function determineStatus($tbuScore)
    {
        if ($tbuScore < -2) {
            return 'stunting';
        }
        return 'normal';
    }

    /**
     * Menghitung skor TB/U berdasarkan tinggi badan anak dan reference standar WHO
     * (Dalam implementasi real, ini akan menggunakan tabel referensi WHO)
     * 
     * @param float $tinggiBadan Tinggi badan dalam cm
     * @param int $umurBulan Umur anak dalam bulan
     * @param string $jenisKelamin 'L' untuk laki-laki, 'P' untuk perempuan
     * @return float|null Skor TB/U dalam SD, null jika data tidak valid
     */
    public static function calculateTBUScore($tinggiBadan, $umurBulan, $jenisKelamin)
    {
        // TODO: Implementasi menggunakan tabel referensi WHO
        // Untuk sekarang, return null untuk mengindikasikan bahwa 
        // data harus disimpan secara manual atau dari sistem lain
        return null;
    }

    /**
     * Mendapatkan deskripsi status gizi
     * 
     * @param string $status 'stunting' atau 'normal'
     * @return string Deskripsi lengkap
     */
    public static function getDescription($status)
    {
        return match($status) {
            'stunting' => 'Tinggi badan pendek menurut umur akibat kekurangan gizi kronis',
            'normal' => 'Pertumbuhan sesuai usia dan status gizi baik',
            default => 'Status tidak diketahui'
        };
    }

    /**
     * Mendapatkan warna badge untuk status gizi
     * 
     * @param string $status 'stunting' atau 'normal'
     * @return string Kode warna hex atau nama warna CSS
     */
    public static function getColor($status)
    {
        return match($status) {
            'stunting' => '#e74c3c', // Red
            'normal' => '#27ae60',   // Green
            default => '#95a5a6'    // Gray
        };
    }

    /**
     * Mendapatkan label display untuk status gizi
     * 
     * @param string $status 'stunting' atau 'normal'
     * @return string Label yang dapat ditampilkan
     */
    public static function getLabel($status)
    {
        return match($status) {
            'stunting' => 'Stunting',
            'normal' => 'Normal',
            default => ucfirst(str_replace('_', ' ', $status))
        };
    }

    /**
     * Validasi apakah status gizi valid
     * 
     * @param string $status
     * @return bool
     */
    public static function isValidStatus($status)
    {
        return in_array($status, ['stunting', 'normal']);
    }

    /**
     * Array semua status gizi yang valid
     * 
     * @return array
     */
    public static function validStatuses()
    {
        return ['stunting', 'normal'];
    }

    /**
     * Array untuk dropdown select (key => label)
     * 
     * @return array
     */
    public static function statusOptions()
    {
        return [
            'normal' => 'Normal (TB/U ≥ -2 SD)',
            'stunting' => 'Stunting (TB/U < -2 SD)',
        ];
    }
}
