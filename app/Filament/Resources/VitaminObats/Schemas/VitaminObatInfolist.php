<?php

namespace App\Filament\Resources\VitaminObats\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VitaminObatInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('balita.id')
                    ->numeric(),
                TextEntry::make('jenis'),
                TextEntry::make('tanggal_pemberian')
                    ->date(),
                TextEntry::make('dosis'),
                TextEntry::make('kader.name')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
