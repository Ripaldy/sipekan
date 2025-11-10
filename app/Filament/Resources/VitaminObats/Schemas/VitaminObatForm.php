<?php

namespace App\Filament\Resources\VitaminObats\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VitaminObatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pemberian Vitamin & Obat')
                    ->schema([
                        Select::make('balita_id')
                            ->label('Balita')
                            ->relationship('balita', 'nama')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),
                        
                        Select::make('jenis')
                            ->label('Jenis')
                            ->options([
                                'vitamin_a' => 'Vitamin A',
                                'obat_cacing' => 'Obat Cacing'
                            ])
                            ->required()
                            ->columnSpan(1),
                        
                        DatePicker::make('tanggal_pemberian')
                            ->label('Tanggal Pemberian')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->maxDate(now())
                            ->columnSpan(1),
                        
                        TextInput::make('dosis')
                            ->label('Dosis')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('Contoh: 100.000 IU atau 400 mg')
                            ->columnSpan(1),
                        
                        Select::make('kader_id')
                            ->label('Kader')
                            ->relationship('kader', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->default(fn () => auth()->id())
                            ->columnSpan(2),
                    ])
                    ->columns(2),
            ]);
    }
}
