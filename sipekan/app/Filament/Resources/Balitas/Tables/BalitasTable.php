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
use Filament\Tables\Actions\EditAction as TableEditAction;
use Filament\Tables\Columns\Column;
use App\Helpers\StatusGiziHelper;

class BalitasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                BadgeColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->colors([
                        'primary' => 'L',
                        'danger' => 'P',
                    ])
                    ->formatStateUsing(fn (string $state): string => $state === 'L' ? 'Laki-laki' : 'Perempuan'),
                
                TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date('Y-m-d')
                    ->sortable(),
                
                TextColumn::make('usia')
                    ->label('Usia')
                    ->getStateUsing(function ($record) {
                        if (!$record->tanggal_lahir) {
                            return '-';
                        }
                        
                        $birthDate = \Carbon\Carbon::parse($record->tanggal_lahir);
                        $today = \Carbon\Carbon::now();
                        
                        $diff = $today->diff($birthDate);
                        
                        return "{$diff->y} tahun {$diff->m} bulan {$diff->d} hari";
                    })
                    ->sortable(),
                
                TextColumn::make('desa_kelurahan')
                    ->label('Desa/Kel')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('nama_orang_tua')
                    ->label('Nama Ortu')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('posyandu')
                    ->label('Posyandu')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('pengukurans')
                    ->label('BB (kg)')
                    ->getStateUsing(function ($record) {
                        $lastMeasurement = $record->pengukurans()
                            ->orderBy('tanggal_ukur', 'desc')
                            ->first();
                        return $lastMeasurement ? $lastMeasurement->berat_badan : '-';
                    })
                    ->sortable(),
                
                TextColumn::make('pengukurans.tinggi_badan')
                    ->label('TB (cm)')
                    ->getStateUsing(function ($record) {
                        $lastMeasurement = $record->pengukurans()
                            ->orderBy('tanggal_ukur', 'desc')
                            ->first();
                        return $lastMeasurement ? $lastMeasurement->tinggi_badan : '-';
                    })
                    ->sortable(),
                
                TextColumn::make('pengukurans.lingkar_kepala')
                    ->label('LILA (cm)')
                    ->getStateUsing(function ($record) {
                        $lastMeasurement = $record->pengukurans()
                            ->orderBy('tanggal_ukur', 'desc')
                            ->first();
                        return $lastMeasurement ? $lastMeasurement->lingkar_kepala : '-';
                    })
                    ->sortable(),
                
                BadgeColumn::make('status_gizi')
                    ->label('Status Gizi')
                    ->getStateUsing(function ($record) {
                        $lastMeasurement = $record->pengukurans()
                            ->orderBy('tanggal_ukur', 'desc')
                            ->first();
                        
                        if (!$lastMeasurement) {
                            return 'Belum ada data';
                        }
                        
                        // Gunakan StatusGiziHelper untuk display label
                        return StatusGiziHelper::getLabel($lastMeasurement->status_gizi);
                    })
                    ->colors([
                        'success' => fn ($state) => $state === 'Normal',
                        'danger' => fn ($state) => $state === 'Stunting',
                    ]),
                
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
