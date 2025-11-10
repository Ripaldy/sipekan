<?php

namespace App\Filament\Resources\Pengukurans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PengukuranInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('balita.id')
                    ->numeric(),
                TextEntry::make('kegiatan.id')
                    ->numeric(),
                TextEntry::make('tanggal_ukur')
                    ->date(),
                TextEntry::make('umur_saat_ukur')
                    ->numeric(),
                TextEntry::make('berat_badan')
                    ->numeric(),
                TextEntry::make('tinggi_badan')
                    ->numeric(),
                TextEntry::make('lingkar_kepala')
                    ->numeric(),
                TextEntry::make('status_gizi'),
                TextEntry::make('kader.name')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
