<?php

namespace App\Filament\Resources\Imunisasis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ImunisasisTable
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
                
                BadgeColumn::make('jenis_vaksin')
                    ->label('Jenis Vaksin')
                    ->searchable()
                    ->colors([
                        'success' => fn ($state) => str_contains($state, 'HB') || str_contains($state, 'BCG'),
                        'warning' => fn ($state) => str_contains($state, 'Polio'),
                        'danger' => fn ($state) => str_contains($state, 'DPT'),
                        'info' => fn ($state) => in_array($state, ['IPV', 'Campak', 'MR']),
                    ]),
                
                TextColumn::make('tanggal_pemberian')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                
                TextColumn::make('umur_saat_imunisasi')
                    ->label('Umur')
                    ->suffix(' bulan')
                    ->sortable(),
                
                TextColumn::make('tempat_pemberian')
                    ->label('Tempat')
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('batch_number')
                    ->label('Batch')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('kader.name')
                    ->label('Kader')
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('jenis_vaksin')
                    ->label('Jenis Vaksin')
                    ->options([
                        'HB-0' => 'HB-0',
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
                        'MR' => 'MR',
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
            ->defaultSort('tanggal_pemberian', 'desc');
    }
}
