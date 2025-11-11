<?php

namespace App\Filament\Widgets;

use App\Models\Kegiatan;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class KegiatanRecentTable extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';
    
    public function getHeading(): ?string
    {
        return 'Kegiatan Recent Table';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Kegiatan::query()
                    ->orderBy('tanggal', 'desc')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('nama_kegiatan')
                    ->label('Nama Kegiatan')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->pemateri ? 'Pemateri: ' . $record->pemateri : null),
                
                TextColumn::make('posyandu')
                    ->label('Posyandu')
                    ->placeholder('-')
                    ->searchable(),
                
                BadgeColumn::make('kategori_kegiatan')
                    ->label('Kategori')
                    ->colors([
                        'success' => 'posyandu',
                        'warning' => 'penimbangan',
                        'danger' => 'imunisasi',
                        'primary' => 'penyuluhan',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'selesai',
                        'warning' => 'terjadwal',
                        'danger' => 'dibatalkan',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable()
                    ->description(function ($record) {
                        if ($record->waktu_mulai && $record->waktu_selesai) {
                            return substr($record->waktu_mulai, 0, 5) . ' - ' . substr($record->waktu_selesai, 0, 5);
                        } elseif ($record->waktu_mulai) {
                            return substr($record->waktu_mulai, 0, 5) . ':00';
                        }
                        return null;
                    }),
                
                TextColumn::make('lokasi')
                    ->label('Lokasi')
                    ->limit(30),
                
                TextColumn::make('target_peserta')
                    ->label('Target')
                    ->suffix(' peserta')
                    ->placeholder('-')
                    ->alignCenter(),
                
                TextColumn::make('jumlah_peserta')
                    ->label('Realisasi')
                    ->suffix(' balita')
                    ->alignCenter()
                    ->getStateUsing(fn ($record) => $record->pengukurans()->distinct('balita_id')->count()),
            ])
            ->defaultSort('tanggal', 'desc');
    }
}
