<?php

namespace App\Filament\Resources\KaderPosyandus\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KaderPosyanduInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->numeric(),
                TextEntry::make('nama_lengkap'),
                TextEntry::make('no_telepon'),
                TextEntry::make('tanggal_bergabung')
                    ->date(),
                TextEntry::make('status'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
