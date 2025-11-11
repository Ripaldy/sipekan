<?php

namespace App\Filament\Resources\Imunisasis\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ImunisasiInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('balita.id')
                    ->numeric(),
                TextEntry::make('jenis_vaksin'),
                TextEntry::make('tanggal_pemberian')
                    ->date(),
                TextEntry::make('umur_saat_imunisasi')
                    ->numeric(),
                TextEntry::make('batch_number'),
                TextEntry::make('tempat_pemberian'),
                TextEntry::make('kader.name')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
