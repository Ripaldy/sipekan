<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengukuran extends Model
{
    protected $fillable = [
        'balita_id',
        'kegiatan_id',
        'tanggal_ukur',
        'umur_saat_ukur',
        'berat_badan',
        'tinggi_badan',
        'lingkar_kepala',
        'status_gizi',
        'catatan',
        'kader_id',
    ];

    protected $casts = [
        'tanggal_ukur' => 'date',
        'berat_badan' => 'decimal:2',
        'tinggi_badan' => 'decimal:2',
        'lingkar_kepala' => 'decimal:2',
    ];

    public function balita(): BelongsTo
    {
        return $this->belongsTo(Balita::class);
    }

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function kader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kader_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($pengukuran) {
            if ($pengukuran->balita && $pengukuran->tanggal_ukur) {
                $pengukuran->umur_saat_ukur = $pengukuran->balita->tanggal_lahir
                    ->diffInMonths($pengukuran->tanggal_ukur);
            }

            if ($pengukuran->berat_badan && $pengukuran->tinggi_badan) {
                $pengukuran->status_gizi = static::calculateStatusGizi(
                    $pengukuran->berat_badan,
                    $pengukuran->tinggi_badan,
                    $pengukuran->umur_saat_ukur
                );
            }
        });
    }

    public static function calculateStatusGizi($beratBadan, $tinggiBadan, $umurBulan): string
    {
        // Standar baru berdasarkan TB/U (Height-for-Age):
        // - Anak Stunting: TB/U < -2 SD
        // - Anak Normal: TB/U >= -2 SD
        
        // Untuk implementasi, ini menggunakan reference WHO untuk TB/U
        // Dalam sistem real, gunakan tabel referensi WHO yang lengkap
        
        // Placeholder: hitung TB/U score (dalam SD)
        // Formula: (Actual Height - Median Height) / SD
        
        // WHO reference medians untuk TB/U (simplified)
        $referenceData = [
            // [age_months => [median, sd], ...]
            // Contoh nilai - gunakan tabel WHO yang lengkap untuk implementasi real
            12 => ['median' => 75.3, 'sd' => 3.3],
            24 => ['median' => 85.3, 'sd' => 3.9],
            36 => ['median' => 94.6, 'sd' => 4.5],
            48 => ['median' => 103.2, 'sd' => 5.0],
            60 => ['median' => 110.3, 'sd' => 5.5],
        ];
        
        // Cari reference terdekat
        $medianHeight = 75;
        $sd = 3;
        
        foreach ($referenceData as $age => $data) {
            if ($umurBulan <= $age) {
                $medianHeight = $data['median'];
                $sd = $data['sd'];
                break;
            }
        }
        
        // Hitung TB/U z-score
        $tbuScore = ($tinggiBadan - $medianHeight) / $sd;
        
        // Tentukan status berdasarkan TB/U < -2 SD
        if ($tbuScore < -2) {
            return 'stunting';      // Tinggi badan pendek menurut umur
        }
        
        return 'normal';            // Pertumbuhan normal
    }

    public function getStatusGiziColorAttribute(): string
    {
        return match($this->status_gizi) {
            'normal' => 'success',    // Green - Normal (TB/U >= -2 SD)
            'stunting' => 'danger',   // Red - Stunting (TB/U < -2 SD)
            default => 'gray',
        };
    }
}