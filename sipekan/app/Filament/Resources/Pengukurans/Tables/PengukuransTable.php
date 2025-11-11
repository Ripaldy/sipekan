<?php

namespace App\Filament\Resources\Pengukurans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PengukuransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('balita.nama')
                    ->label('Nama Balita')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('balita.id_balita')
                    ->label('ID Balita')
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('tanggal_ukur')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                
                TextColumn::make('umur_saat_ukur')
                    ->label('Umur')
                    ->suffix(' bulan')
                    ->sortable(),
                
                TextColumn::make('berat_badan')
                    ->label('BB')
                    ->suffix(' kg')
                    ->sortable(),
                
                TextColumn::make('tinggi_badan')
                    ->label('TB')
                    ->suffix(' cm')
                    ->sortable(),
                
                TextColumn::make('lingkar_kepala')
                    ->label('LK')
                    ->suffix(' cm')
                    ->toggleable(),
                
                BadgeColumn::make('status_gizi')
                    ->label('Status Gizi')
                    ->colors([
                        'success' => 'normal',
                        'warning' => 'kurang',
                        'danger' => 'buruk',
                        'info' => 'lebih',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                
                TextColumn::make('kegiatan.nama_kegiatan')
                    ->label('Kegiatan')
                    ->toggleable()
                    ->searchable(),
                
                TextColumn::make('kader.name')
                    ->label('Kader')
                    ->toggleable()
                    ->searchable(),
                
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status_gizi')
                    ->label('Status Gizi')
                    ->options([
                        'normal' => 'Normal',
                        'kurang' => 'Kurang',
                        'buruk' => 'Buruk',
                        'lebih' => 'Lebih',
                    ]),
                SelectFilter::make('balita_id')
                    ->label('Balita')
                    ->relationship('balita', 'nama')
                    ->searchable()
                    ->preload(),
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
            ->defaultSort('tanggal_ukur', 'desc');
    }
}
