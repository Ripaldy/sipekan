<?php

namespace App\Filament\Resources\Kegiatans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KegiatansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kegiatan')
                    ->label('Nama Kegiatan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => 'Pemateri: ' . ($record->pemateri ?? '-')),
                
                TextColumn::make('posyandu')
                    ->label('Posyandu')
                    ->searchable()
                    ->sortable(),
                
                BadgeColumn::make('kategori_kegiatan')
                    ->label('Kategori')
                    ->colors([
                        'success' => 'imunisasi',
                        'warning' => 'penimbangan',
                        'info' => 'penyuluhan',
                        'primary' => 'posyandu',
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'imunisasi' => 'Imunisasi',
                        'penimbangan' => 'Penimbangan',
                        'penyuluhan' => 'Penyuluhan',
                        'posyandu' => 'Posyandu',
                        default => ucfirst($state),
                    }),
                
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'selesai',
                        'warning' => 'terjadwal',
                        'info' => 'sedang berlangsung',
                        'danger' => 'dibatalkan',
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'selesai' => 'Selesai',
                        'terjadwal' => 'Terjadwal',
                        'sedang berlangsung' => 'Sedang Berlangsung',
                        'dibatalkan' => 'Dibatalkan',
                        default => ucfirst($state),
                    }),
                
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('Y-m-d')
                    ->sortable()
                    ->description(fn ($record) => $record->waktu_mulai ? $record->waktu_mulai . ' - ' . $record->waktu_selesai : null),
                
                TextColumn::make('lokasi')
                    ->label('Lokasi')
                    ->searchable()
                    ->limit(30),
                
                TextColumn::make('target_peserta')
                    ->label('Target')
                    ->suffix(' peserta'),
                
                TextColumn::make('jumlah_peserta')
                    ->label('Realisasi')
                    ->suffix(' balita'),
                
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('kategori_kegiatan')
                    ->label('Kategori')
                    ->options([
                        'imunisasi' => 'Imunisasi',
                        'penimbangan' => 'Penimbangan',
                        'penyuluhan' => 'Penyuluhan',
                        'posyandu' => 'Posyandu',
                    ]),
                
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'terjadwal' => 'Terjadwal',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('tanggal', 'desc');
    }
}
