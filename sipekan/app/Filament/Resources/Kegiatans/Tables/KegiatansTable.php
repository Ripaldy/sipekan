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
                    ->description(fn ($record) => $record->pemateri ? 'Pemateri: ' . $record->pemateri : null),
                
                TextColumn::make('posyandu')
                    ->label('Posyandu')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->placeholder('-'),
                
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
                    ->description(fn ($record) => $record->waktu_mulai ? $record->waktu_mulai . ' - ' . $record->waktu_selesai : null),
                
                TextColumn::make('lokasi')
                    ->label('Lokasi')
                    ->searchable()
                    ->toggleable()
                    ->limit(30),
                
                TextColumn::make('target_peserta')
                    ->label('Target')
                    ->suffix(' peserta')
                    ->toggleable()
                    ->placeholder('-'),
                
                TextColumn::make('jumlah_peserta')
                    ->label('Realisasi')
                    ->suffix(' balita')
                    ->toggleable(),
                
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
