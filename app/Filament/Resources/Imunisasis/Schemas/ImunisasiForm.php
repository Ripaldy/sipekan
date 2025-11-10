<?php

namespace App\Filament\Resources\Imunisasis\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ImunisasiForm
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
                
                Section::make('Data Imunisasi')
                    ->schema([
                        Select::make('jenis_vaksin')
                            ->label('Jenis Vaksin')
                            ->options([
                                'HB-0' => 'HB-0 (Hepatitis B)',
                                'BCG' => 'BCG',
                                'Polio 1' => 'Polio 1',
                                'Polio 2' => 'Polio 2',
                                'Polio 3' => 'Polio 3',
                                'Polio 4' => 'Polio 4',
                                'DPT-HB-Hib 1' => 'DPT-HB-Hib 1',
                                'DPT-HB-Hib 2' => 'DPT-HB-Hib 2',
                                'DPT-HB-Hib 3' => 'DPT-HB-Hib 3',
                                'IPV' => 'IPV',
                                'Campak' => 'Campak',
                                'MR' => 'MR (Measles Rubella)',
                            ])
                            ->required()
                            ->searchable()
                            ->columnSpan(2),
                        
                        DatePicker::make('tanggal_pemberian')
                            ->label('Tanggal Pemberian')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->maxDate(now())
                            ->columnSpan(1),
                        
                        TextInput::make('umur_saat_imunisasi')
                            ->label('Umur Saat Imunisasi (bulan)')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Akan dihitung otomatis')
                            ->columnSpan(1),
                        
                        TextInput::make('tempat_pemberian')
                            ->label('Tempat Pemberian')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Contoh: Puskesmas XYZ')
                            ->columnSpan(1),
                        
                        TextInput::make('batch_number')
                            ->label('Batch Number')
                            ->maxLength(50)
                            ->placeholder('Opsional')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
            ]);
    }
}
