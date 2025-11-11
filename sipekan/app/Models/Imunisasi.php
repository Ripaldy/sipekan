<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Imunisasi extends Model
{
    protected $fillable = [
        'balita_id',
        'jenis_vaksin',
        'tanggal_pemberian',
        'umur_saat_imunisasi',
        'batch_number',
        'tempat_pemberian',
        'kader_id',
    ];

    protected $casts = [
        'tanggal_pemberian' => 'date',
    ];

    public function balita(): BelongsTo
    {
        return $this->belongsTo(Balita::class);
    }

    public function kader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kader_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($imunisasi) {
            if ($imunisasi->balita && $imunisasi->tanggal_pemberian) {
                $imunisasi->umur_saat_imunisasi = $imunisasi->balita->tanggal_lahir
                    ->diffInMonths($imunisasi->tanggal_pemberian);
            }
        });
    }

    public static function getJadwalImunisasi(): array
    {
        return [
            'HB-0' => 0,
            'BCG' => 1,
            'Polio 1' => 1,
            'DPT-HB-Hib 1' => 2,
            'Polio 2' => 2,
            'DPT-HB-Hib 2' => 3,
            'Polio 3' => 3,
            'DPT-HB-Hib 3' => 4,
            'Polio 4' => 4,
            'IPV' => 4,
            'Campak' => 9,
            'MR' => 9,
        ];
    }
}