<?php

namespace App\Filament\Resources\Balitas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Helpers\StatusGiziHelper;

class BalitaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Personal')
                    ->description('Data dasar balita')
                    ->schema([
                        TextEntry::make('id_balita')
                            ->label('ID Balita')
                            ->copyable(),
                        
                        TextEntry::make('nama')
                            ->label('Nama')
                            ->weight('bold'),
                        
                        TextEntry::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->formatStateUsing(fn ($state) => $state === 'L' ? 'Laki-laki' : 'Perempuan'),
                        
                        TextEntry::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->date('d M Y'),
                        
                        TextEntry::make('nama_orang_tua')
                            ->label('Nama Orang Tua'),
                        
                        TextEntry::make('no_telepon_ortu')
                            ->label('No. Telepon Orang Tua')
                            ->copyable(),
                    ])
                    ->columns(2),
                
                Section::make('Lokasi & Alamat')
                    ->description('Informasi tempat tinggal')
                    ->schema([
                        TextEntry::make('desa_kelurahan')
                            ->label('Desa/Kelurahan'),
                        
                        TextEntry::make('posyandu')
                            ->label('Posyandu'),
                        
                        TextEntry::make('alamat')
                            ->label('Alamat Lengkap')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                
                Section::make('Riwayat Sistem')
                    ->description('Data teknis')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime('d M Y H:i'),
                        
                        TextEntry::make('updated_at')
                            ->label('Diupdate')
                            ->dateTime('d M Y H:i'),
                        
                        TextEntry::make('deleted_at')
                            ->label('Dihapus')
                            ->dateTime('d M Y H:i')
                            ->placeholder('Belum dihapus'),
                    ])
                    ->columns(2)
                    ->collapsed(true),
                
                Section::make('Riwayat Pengukuran')
                    ->description('Tabel riwayat pengukuran balita')
                    ->schema([
                        TextEntry::make('pengukuran_html')
                            ->label('')
                            ->hiddenLabel()
                            ->html()
                            ->getStateUsing(function ($record) {
                                $pengukurans = $record->pengukurans()
                                    ->orderBy('tanggal_ukur', 'asc')
                                    ->get();
                                
                                if ($pengukurans->isEmpty()) {
                                    return '<div style="text-align: center; padding: 20px; color: #999;">📊 Belum ada data pengukuran</div>';
                                }
                                
                                $html = '<div style="width: 100%; overflow-x: auto;">';
                                $html .= '<table style="width: 100%; border-collapse: collapse;">';
                                $html .= '<thead>';
                                $html .= '<tr style="background-color: #27ae60; color: white;">';
                                $html .= '<th style="border: 1px solid #27ae60; padding: 12px; text-align: center; font-weight: bold; font-size: 13px;">No</th>';
                                $html .= '<th style="border: 1px solid #27ae60; padding: 12px; text-align: center; font-weight: bold; font-size: 13px;">Tanggal</th>';
                                $html .= '<th style="border: 1px solid #27ae60; padding: 12px; text-align: center; font-weight: bold; font-size: 13px;">BB (kg)</th>';
                                $html .= '<th style="border: 1px solid #27ae60; padding: 12px; text-align: center; font-weight: bold; font-size: 13px;">TB (cm)</th>';
                                $html .= '<th style="border: 1px solid #27ae60; padding: 12px; text-align: center; font-weight: bold; font-size: 13px;">LILA (cm)</th>';
                                $html .= '<th style="border: 1px solid #27ae60; padding: 12px; text-align: center; font-weight: bold; font-size: 13px;">Status Gizi</th>';
                                $html .= '</tr></thead><tbody>';
                                
                                $no = 1;
                                foreach ($pengukurans as $p) {
                                    $color = $p->status_gizi === 'normal' 
                                        ? StatusGiziHelper::getColor('normal')
                                        : StatusGiziHelper::getColor('stunting');
                                    
                                    $statusClass = 'background-color: ' . $color . '; color: white;';
                                    
                                    $statusText = StatusGiziHelper::getLabel($p->status_gizi);
                                    
                                    $rowBg = $no % 2 === 0 ? 'background-color: #f8f8f8;' : 'background-color: #ffffff;';
                                    
                                    $html .= '<tr style="' . $rowBg . '">';
                                    $html .= '<td style="border: 1px solid #e0e0e0; padding: 12px; text-align: center; font-size: 13px;">' . $no . '</td>';
                                    $html .= '<td style="border: 1px solid #e0e0e0; padding: 12px; text-align: center; font-size: 13px;">' . $p->tanggal_ukur->format('d M Y') . '</td>';
                                    $html .= '<td style="border: 1px solid #e0e0e0; padding: 12px; text-align: center; font-size: 13px; font-weight: 600;">' . number_format($p->berat_badan, 2) . '</td>';
                                    $html .= '<td style="border: 1px solid #e0e0e0; padding: 12px; text-align: center; font-size: 13px; font-weight: 600;">' . number_format($p->tinggi_badan, 2) . '</td>';
                                    $html .= '<td style="border: 1px solid #e0e0e0; padding: 12px; text-align: center; font-size: 13px; font-weight: 600;">' . number_format($p->lingkar_kepala, 2) . '</td>';
                                    $html .= '<td style="border: 1px solid #e0e0e0; padding: 12px; text-align: center;"><span style="display: inline-block; padding: 4px 10px; border-radius: 3px; font-size: 12px; font-weight: 600; ' . $statusClass . '">' . $statusText . '</span></td>';
                                    $html .= '</tr>';
                                    $no++;
                                }
                                
                                $html .= '</tbody></table>';
                                $html .= '</div>';
                                return $html;
                            }),
                    ])
                    ->collapsed(false),
                
                

            ]);
    }
}
