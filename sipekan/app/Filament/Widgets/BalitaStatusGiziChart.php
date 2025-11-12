<?php

namespace App\Filament\Widgets;

use App\Models\Pengukuran;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use App\Helpers\StatusGiziHelper;

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
        // Ambil pengukuran terakhir setiap balita berdasarkan standar baru:
        // - Stunting: TB/U < -2 SD
        // - Normal: TB/U >= -2 SD
        $statusGizi = Pengukuran::select('balita_id', 'status_gizi', 'tanggal_ukur')
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('pengukurans')
                    ->groupBy('balita_id');
            })
            ->get()
            ->groupBy('status_gizi')
            ->map(fn ($group) => $group->count());

        // Hitung jumlah Stunting dan Normal berdasarkan standar baru
        $stunting = $statusGizi->get('stunting', 0);
        $normal = $statusGizi->get('normal', 0);

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Anak',
                    'data' => [$stunting, $normal],
                    'backgroundColor' => [
                        'rgb(231, 76, 60)',    // Red - Stunting (TB/U < -2 SD)
                        'rgb(39, 174, 96)',    // Green - Normal (TB/U >= -2 SD)
                    ],
                ],
            ],
            'labels' => [
                'Stunting (TB/U < -2 SD): ' . $stunting . ' anak', 
                'Normal (TB/U ≥ -2 SD): ' . $normal . ' anak'
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
