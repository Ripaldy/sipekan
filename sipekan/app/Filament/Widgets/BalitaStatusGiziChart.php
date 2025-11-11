<?php

namespace App\Filament\Widgets;

use App\Models\Pengukuran;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class BalitaStatusGiziChart extends ChartWidget
{
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];
    
    public function getHeading(): ?string
    {
        return 'Perbandingan Status Gizi Anak';
    }

    protected function getData(): array
    {
        // Ambil pengukuran terakhir setiap balita berdasarkan WHO categories
        $statusGizi = Pengukuran::select('balita_id', 'status_gizi', 'tanggal_ukur')
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('pengukurans')
                    ->groupBy('balita_id');
            })
            ->get()
            ->groupBy('status_gizi')
            ->map(fn ($group) => $group->count());

        // WHO 5 categories
        $giziBuruk = $statusGizi->get('gizi_buruk', 0);
        $kurus = $statusGizi->get('kurus', 0);
        $normal = $statusGizi->get('normal', 0);
        $gemuk = $statusGizi->get('gemuk', 0);
        $obesitas = $statusGizi->get('obesitas', 0);

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Anak',
                    'data' => [$giziBuruk, $kurus, $normal, $gemuk, $obesitas],
                    'backgroundColor' => [
                        'rgb(220, 38, 38)',    // Red - Gizi Buruk
                        'rgb(251, 146, 60)',   // Orange - Kurus
                        'rgb(34, 197, 94)',    // Green - Normal
                        'rgb(234, 179, 8)',    // Yellow - Gemuk
                        'rgb(29, 78, 216)',    // Blue - Obesitas
                    ],
                ],
            ],
            'labels' => [
                'Gizi Buruk: ' . $giziBuruk . ' anak', 
                'Kurus: ' . $kurus . ' anak', 
                'Normal: ' . $normal . ' anak', 
                'Gemuk: ' . $gemuk . ' anak', 
                'Obesitas: ' . $obesitas . ' anak'
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
    
    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => true,
            'aspectRatio' => 1,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
