<?php

namespace App\Filament\Resources\Balitas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BalitaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_balita'),
                TextEntry::make('nama'),
                TextEntry::make('jenis_kelamin'),
                TextEntry::make('tanggal_lahir')
                    ->date(),
                TextEntry::make('nama_orang_tua'),
                TextEntry::make('alamat'),
                TextEntry::make('desa_kelurahan'),
                TextEntry::make('posyandu'),
                TextEntry::make('no_telepon_ortu'),
                TextEntry::make('foto_balita'),
                TextEntry::make('deleted_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
