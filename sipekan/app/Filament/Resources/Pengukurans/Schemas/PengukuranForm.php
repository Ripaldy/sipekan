<?php

namespace App\Filament\Resources\Pengukurans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PengukuranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Balita')
                    ->schema([
                        Select::make('balita_id')
                            ->label('Balita')
                            ->relationship('balita', 'nama')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),
                        
                        Select::make('kegiatan_id')
                            ->label('Kegiatan')
                            ->relationship('kegiatan', 'nama_kegiatan')
                            ->searchable()
                            ->preload()
                            ->columnSpan(1),
                        
                        DatePicker::make('tanggal_ukur')
                            ->label('Tanggal Pengukuran')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->maxDate(now())
                            ->columnSpan(1),
                        
                        Select::make('kader_id')
                            ->label('Kader')
                            ->relationship('kader', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->default(fn () => auth()->id())
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                
                Section::make('Data Pengukuran')
                    ->schema([
                        TextInput::make('berat_badan')
                            ->label('Berat Badan (kg)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(50)
                            ->step(0.1)
                            ->suffix('kg')
                            ->columnSpan(1),
                        
                        TextInput::make('tinggi_badan')
                            ->label('Tinggi Badan (cm)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(150)
                            ->step(0.1)
                            ->suffix('cm')
                            ->columnSpan(1),
                        
                        TextInput::make('lingkar_kepala')
                            ->label('Lingkar Kepala (cm)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(70)
                            ->step(0.1)
                            ->suffix('cm')
                            ->columnSpan(1),
                        
                        TextInput::make('umur_saat_ukur')
                            ->label('Umur Saat Ukur (bulan)')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Akan dihitung otomatis')
                            ->columnSpan(1),
                        
                        Select::make('status_gizi')
                            ->label('Status Gizi')
                            ->options([
                                'normal' => 'Normal',
                                'kurang' => 'Kurang',
                                'buruk' => 'Buruk',
                                'lebih' => 'Lebih'
                            ])
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Akan dihitung otomatis')
                            ->columnSpan(2),
                        
                        Textarea::make('catatan')
                            ->label('Catatan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
