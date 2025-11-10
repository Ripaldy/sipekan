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
        // Simplified BMI calculation for children
        $tinggiBadanMeter = $tinggiBadan / 100;
        $bmi = $beratBadan / ($tinggiBadanMeter * $tinggiBadanMeter);

        if ($umurBulan < 24) {
            if ($bmi < 14) return 'buruk';
            if ($bmi < 16) return 'kurang';
            if ($bmi < 18) return 'normal';
            return 'lebih';
        } else {
            if ($bmi < 13) return 'buruk';
            if ($bmi < 15) return 'kurang';
            if ($bmi < 17) return 'normal';
            return 'lebih';
        }
    }

    public function getStatusGiziColorAttribute(): string
    {
        return match($this->status_gizi) {
            'normal' => 'success',
            'kurang' => 'warning',
            'buruk' => 'danger',
            'lebih' => 'info',
            default => 'gray',
        };
    }
}