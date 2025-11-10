<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Balita extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id_balita',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'nik_balita',
        'nama_orang_tua',
        'nik_orang_tua',
        'alamat',
        'rt_rw',
        'no_telepon_ortu',
        'foto_balita',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    protected $appends = ['umur_sekarang'];

    // Relationships
    public function pengukurans(): HasMany
    {
        return $this->hasMany(Pengukuran::class);
    }

    public function imunisasis(): HasMany
    {
        return $this->hasMany(Imunisasi::class);
    }

    public function vitaminObats(): HasMany
    {
        return $this->hasMany(VitaminObat::class);
    }

    // Accessors
    public function getUmurSekarangAttribute(): int
    {
        return $this->tanggal_lahir->diffInMonths(now());
    }

    public function getUmurDisplayAttribute(): string
    {
        $months = $this->umur_sekarang;
        $years = floor($months / 12);
        $remainingMonths = $months % 12;

        if ($years > 0) {
            return "{$years} tahun {$remainingMonths} bulan";
        }

        return "{$months} bulan";
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto_balita ? Storage::disk('public')->url($this->foto_balita) : null;
    }

    // Helpers
    public function pengukuranTerakhir()
    {
        return $this->pengukurans()->latest('tanggal_ukur')->first();
    }

    public function getStatusGiziTerakhir(): string
    {
        $pengukuranTerakhir = $this->pengukuranTerakhir();
        return $pengukuranTerakhir ? ucfirst($pengukuranTerakhir->status_gizi) : 'Belum ada data';
    }
}