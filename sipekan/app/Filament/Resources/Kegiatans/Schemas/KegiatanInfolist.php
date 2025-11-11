<?php

namespace App\Filament\Resources\Kegiatans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KegiatanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nama_kegiatan'),
                TextEntry::make('tanggal')
                    ->date(),
                TextEntry::make('waktu_mulai')
                    ->time(),
                TextEntry::make('waktu_selesai')
                    ->time(),
                TextEntry::make('lokasi'),
                TextEntry::make('kategori_kegiatan'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
