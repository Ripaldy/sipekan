<?php

namespace App\Filament\Resources\Kegiatans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KegiatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Section 1: Informasi Dasar
                Section::make('Informasi Dasar')
                    ->description('Judul dan deskripsi kegiatan')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextInput::make('nama_kegiatan')
                            ->label('Judul Kegiatan')
                            ->placeholder('Contoh: Imunisasi Anak Balita 1')
                            ->required()
                            ->maxLength(100)
                            ->columnSpanFull(),
                        
                        Textarea::make('deskripsi')
                            ->label('Deskripsi Kegiatan')
                            ->placeholder('Jelaskan detail kegiatan...')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->collapsible(),
                
                // Section 2: Jadwal & Lokasi
                Section::make('Jadwal & Lokasi')
                    ->description('Waktu dan tempat pelaksanaan')
                    ->icon('heroicon-o-calendar')
                    ->schema([
                        DatePicker::make('tanggal')
                            ->label('Tanggal Kegiatan')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->placeholder('mm/dd/yyyy')
                            ->columnSpan(2),
                        
                        TimePicker::make('waktu_mulai')
                            ->label('Waktu Mulai')
                            ->native(false)
                            ->seconds(false)
                            ->placeholder('HH:MM')
                            ->columnSpan(1),
                        
                        TimePicker::make('waktu_selesai')
                            ->label('Waktu Selesai')
                            ->native(false)
                            ->seconds(false)
                            ->placeholder('HH:MM')
                            ->columnSpan(1),
                        
                        TextInput::make('posyandu')
                            ->label('Posyandu')
                            ->placeholder('Contoh: Posyandu Anggrek')
                            ->maxLength(100)
                            ->columnSpan(2),
                        
                        TextInput::make('lokasi')
                            ->label('Lokasi')
                            ->placeholder('Contoh: Jl. Merdeka No. 45, Kecamatan A')
                            ->required()
                            ->maxLength(100)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),
                
                // Section 3: Kategori & Status
                Section::make('Kategori & Status')
                    ->description('Jenis dan status kegiatan')
                    ->icon('heroicon-o-tag')
                    ->schema([
                        Select::make('kategori_kegiatan')
                            ->label('Kategori')
                            ->options([
                                'imunisasi' => 'Imunisasi',
                                'penimbangan' => 'Penimbangan',
                                'penyuluhan' => 'Penyuluhan',
                                'posyandu' => 'Posyandu',
                            ])
                            ->required()
                            ->placeholder('Pilih kategori')
                            ->columnSpan(1),
                        
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'terjadwal' => 'Terjadwal',
                                'sedang berlangsung' => 'Sedang Berlangsung',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                            ])
                            ->required()
                            ->default('terjadwal')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),
                
                // Section 4: Pemateri & Target
                Section::make('Pemateri & Target')
                    ->description('Penanggung jawab dan target peserta')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        TextInput::make('pemateri')
                            ->label('Pemateri/Penanggung Jawab')
                            ->placeholder('Contoh: Dr. Siti Nurhaliza')
                            ->maxLength(100)
                            ->columnSpan(1),
                        
                        TextInput::make('target_peserta')
                            ->label('Target Peserta')
                            ->placeholder('Contoh: 50 anak')
                            ->numeric()
                            ->suffix('peserta')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}
