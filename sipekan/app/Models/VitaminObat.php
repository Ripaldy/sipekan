<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VitaminObat extends Model
{
    protected $fillable = [
        'balita_id',
        'jenis',
        'tanggal_pemberian',
        'dosis',
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
}