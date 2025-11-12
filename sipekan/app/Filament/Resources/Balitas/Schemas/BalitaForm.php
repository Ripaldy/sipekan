<?php

namespace App\Filament\Resources\Balitas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Helpers\StatusGiziHelper;

class BalitaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Balita')
                    ->schema([
                        TextInput::make('id_balita')
                            ->label('ID Balita')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Auto-generate: BSY-YYYYMMDD-XXX')
                            ->helperText('ID akan di-generate otomatis'),
                        
                        TextInput::make('nama')
                            ->label('Nama Balita')
                            ->required()
                            ->maxLength(100)
                            ->columnSpan(1),
                        
                        Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ])
                            ->required()
                            ->columnSpan(1),
                        
                        DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->maxDate(now())
                            ->columnSpan(2),
                        
                        FileUpload::make('foto_balita')
                            ->label('Foto Balita')
                            ->image()
                            ->imageEditor()
                            ->maxSize(2048)
                            ->directory('balita-photos')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                
                Section::make('Informasi Orang Tua & Lokasi')
                    ->schema([
                        TextInput::make('nama_orang_tua')
                            ->label('Nama Orang Tua/Wali')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Masukkan nama orang tua/wali')
                            ->columnSpanFull(),
                        
                        TextInput::make('desa_kelurahan')
                            ->label('Desa/Kel')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Masukkan nama desa/kelurahan')
                            ->columnSpan(1),
                        
                        TextInput::make('posyandu')
                            ->label('Posyandu')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Cth: Posyandu Anggrek')
                            ->columnSpan(1),
                        
                        Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        TextInput::make('no_telepon_ortu')
                            ->label('No. Telepon Orang Tua')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->placeholder('08xxxxxxxxxx')
                            ->columnSpan(2),
                    ])
                    ->columns(2),
                
                Section::make('Status Gizi Pengukuran Terakhir')
                    ->description('Berdasarkan standar: Stunting (TB/U < -2 SD), Normal (TB/U ≥ -2 SD)')
                    ->schema([
                        Select::make('status_gizi_edit')
                            ->label('Status Gizi')
                            ->options(StatusGiziHelper::statusOptions())
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $record) {
                                if ($record && StatusGiziHelper::isValidStatus($state)) {
                                    $lastMeasurement = $record->pengukurans()
                                        ->orderBy('tanggal_ukur', 'desc')
                                        ->first();
                                    
                                    if ($lastMeasurement) {
                                        $lastMeasurement->status_gizi = $state;
                                        $lastMeasurement->save();
                                    }
                                }
                            })
                            ->default(function ($record) {
                                if ($record) {
                                    $lastMeasurement = $record->pengukurans()
                                        ->orderBy('tanggal_ukur', 'desc')
                                        ->first();
                                    return $lastMeasurement ? $lastMeasurement->status_gizi : null;
                                }
                                return null;
                            }),
                    ])
                    ->collapsed(true),
            ]);
    }
}
