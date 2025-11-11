<?php

namespace App\Filament\Resources\Balitas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class BalitasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_balita')
                    ->label('ID Balita')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                
                ImageColumn::make('foto_balita')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png'))
                    ->toggleable(),
                
                TextColumn::make('nama')
                    ->label('Nama Balita')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->umur_display),
                
                BadgeColumn::make('jenis_kelamin')
                    ->label('L/P')
                    ->colors([
                        'primary' => 'L',
                        'danger' => 'P',
                    ])
                    ->formatStateUsing(fn (string $state): string => $state === 'L' ? 'Laki-laki' : 'Perempuan'),
                
                TextColumn::make('tanggal_lahir')
                    ->label('Tgl Lahir')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('nama_orang_tua')
                    ->label('Nama Orang Tua')
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('no_telepon_ortu')
                    ->label('No. Telepon')
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('desa_kelurahan')
                    ->label('Desa/Kel')
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('posyandu')
                    ->label('Posyandu')
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('deleted_at')
                    ->label('Dihapus')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
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
                SelectFilter::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
