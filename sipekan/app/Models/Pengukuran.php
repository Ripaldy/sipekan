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
        // WHO standard BB/TB (Weight-for-Height) Z-score calculation
        // Simplified calculation using BMI-for-age as proxy
        $tinggiBadanMeter = $tinggiBadan / 100;
        $bmi = $beratBadan / ($tinggiBadanMeter * $tinggiBadanMeter);

        // WHO reference values approximation for Indonesian children
        // These are simplified - in production, use actual WHO tables
        if ($umurBulan < 24) {
            $median = 16.5;
            $sd = 1.5;
        } elseif ($umurBulan < 60) {
            $median = 15.5;
            $sd = 1.4;
        } else {
            $median = 15.0;
            $sd = 1.6;
        }

        // Calculate Z-score
        $zScore = ($bmi - $median) / $sd;

        // WHO categories based on Z-score
        if ($zScore < -3) return 'gizi_buruk';      // Severely wasted (< -3 SD)
        if ($zScore < -2) return 'kurus';           // Wasted (-3 to -2 SD)
        if ($zScore <= 2) return 'normal';          // Normal (-2 to +2 SD)
        if ($zScore <= 3) return 'gemuk';           // Overweight (+2 to +3 SD)
        return 'obesitas';                          // Obese (> +3 SD)
    }

    public function getStatusGiziColorAttribute(): string
    {
        return match($this->status_gizi) {
            'normal' => 'success',
            'kurus' => 'warning',
            'gizi_buruk' => 'danger',
            'gemuk' => 'info',
            'obesitas' => 'primary',
            default => 'gray',
        };
    }
}