<?php

namespace App\Filament\Widgets;

use App\Models\Balita;
use App\Models\Kegiatan;
use App\Models\Imunisasi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use App\Helpers\StatusGiziHelper;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Total Anak Terdaftar
        $totalBalita = Balita::count();
        
        // Total Kegiatan (sudah dilaksanakan)
        $totalKegiatan = Kegiatan::where('status', 'selesai')->count();
        
        // Gejala Stunting - anak dengan status_gizi 'stunting' (standar TB/U < -2 SD)
        $gejalaStunting = DB::table('pengukurans')
            ->select('balita_id')
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('pengukurans')
                    ->groupBy('balita_id');
            })
            ->where('status_gizi', 'stunting')
            ->distinct()
            ->count('balita_id');
        
        // Anak Normal - anak dengan status_gizi 'normal' (standar TB/U >= -2 SD)
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
                ->description('Anak dengan TB/U < -2 SD (kekurangan gizi kronis)')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger')
                ->chartColor('#e74c3c')
                ->extraAttributes(['class' => 'stat-danger']),
            
            Stat::make('Anak Normal', $anakNormal)
                ->description('Anak dengan TB/U ≥ -2 SD (pertumbuhan normal)')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chartColor('#27ae60')
                ->extraAttributes(['class' => 'stat-success']),
        ];
    }
}
