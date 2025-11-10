<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kegiatan extends Model
{
    protected $fillable = [
        'nama_kegiatan',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'kategori_kegiatan',
        'deskripsi',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pengukurans(): HasMany
    {
        return $this->hasMany(Pengukuran::class);
    }

    public function getJumlahPesertaAttribute(): int
    {
        return $this->pengukurans()->distinct('balita_id')->count();
    }

    public function scopeUpcoming($query)
    {
        return $query->where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal', 'asc');
    }

    public function scopeBulanIni($query)
    {
        return $query->whereYear('tanggal', now()->year)
            ->whereMonth('tanggal', now()->month);
    }
}