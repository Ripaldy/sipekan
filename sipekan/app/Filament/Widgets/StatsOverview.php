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
    protected string $view = 'filament.widgets.stats-overview';

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
                ->color('info')
                ->chartColor('#3498db')
                ->extraAttributes(['class' => 'stat-info']),
            
            Stat::make('Total Kegiatan', $totalKegiatan)
                ->description('Kegiatan telah dilaksanakan')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning')
                ->chartColor('#f1c40f')
                ->extraAttributes(['class' => 'stat-warning']),
            
            Stat::make('Gejala Stunting', $gejalaStunting)
                ->description('Data anak dengan resiko stunting')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger')
                ->chartColor('#e74c3c')
                ->extraAttributes(['class' => 'stat-danger']),
            
            Stat::make('Anak Normal', $anakNormal)
                ->description('Anak dengan pertumbuhan normal')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chartColor('#27ae60')
                ->extraAttributes(['class' => 'stat-success']),
        ];
    }

    protected function getStatColor(string $color): string
    {
        return match($color) {
            'info' => '#3498db',
            'warning' => '#f1c40f',
            'danger' => '#e74c3c',
            'success' => '#27ae60',
            default => '#6b7280',
        };
    }
}
