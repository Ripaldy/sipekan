<?php

namespace App\Filament\Resources\KaderPosyandus\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class KaderPosyanduForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('nama_lengkap')
                    ->required(),
                TextInput::make('no_telepon')
                    ->tel()
                    ->required(),
                Textarea::make('alamat')
                    ->required()
                    ->columnSpanFull(),
                DatePicker::make('tanggal_bergabung')
                    ->required(),
                Select::make('status')
                    ->options(['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'])
                    ->default('aktif')
                    ->required(),
            ]);
    }
}
