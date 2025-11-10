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
        'nama_orang_tua',
        'alamat',
        'desa_kelurahan',
        'posyandu',
        'no_telepon_ortu',
        'foto_balita',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    protected $appends = ['umur_sekarang'];

    // Boot method untuk auto-generate id_balita
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($balita) {
            if (empty($balita->id_balita)) {
                $balita->id_balita = static::generateIdBalita();
            }
        });
    }

    /**
     * Generate ID Balita dengan format: BSY-YYYYMMDD-XXX
     */
    public static function generateIdBalita(): string
    {
        $today = now()->format('Ymd');
        $prefix = "BSY-{$today}-";
        
        // Cari balita terakhir dengan prefix hari ini
        $lastBalita = static::withTrashed()
            ->where('id_balita', 'like', $prefix . '%')
            ->orderBy('id_balita', 'desc')
            ->first();
        
        if ($lastBalita) {
            // Extract nomor urut terakhir
            $lastNumber = (int) substr($lastBalita->id_balita, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

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
        return $this->foto_balita ? asset('storage/' . $this->foto_balita) : null;
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