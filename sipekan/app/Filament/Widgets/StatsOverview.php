<?php

namespace App\Filament\Widgets;

use App\Models\Balita;
use App\Models\Kegiatan;
use App\Models\Imunisasi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Total Anak Terdaftar
        $totalBalita = Balita::count();
        
        // Total Kegiatan (sudah dilaksanakan)
        $totalKegiatan = Kegiatan::where('status', 'selesai')->count();
        
        // Gejala Stunting - anak dengan gizi buruk atau kurus (WHO categories)
        $gejalaStunting = DB::table('pengukurans')
            ->select('balita_id')
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('pengukurans')
                    ->groupBy('balita_id');
            })
            ->whereIn('status_gizi', ['gizi_buruk', 'kurus'])
            ->distinct()
            ->count('balita_id');
        
        // Anak Normal - anak dengan status gizi normal (WHO category)
        $anakNormal = DB::table('pengukurans')
            ->select('balita_id')
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('pengukurans')
                    ->groupBy('balita_id');
            })
            ->where('status_gizi', 'normal')
            ->distinct()
            ->count('balita_id');

        return [
            Stat::make('Anak Terdaftar', $totalBalita)
                ->description('Total anak dalam sistem')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
            
            Stat::make('Total Kegiatan', $totalKegiatan)
                ->description('Kegiatan telah dilaksanakan')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning'),
            
            Stat::make('Gejala Stunting', $gejalaStunting)
                ->description('Data anak dengan resiko stunting')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
            
            Stat::make('Anak Normal', $anakNormal)
                ->description('Anak dengan pertumbuhan normal')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
