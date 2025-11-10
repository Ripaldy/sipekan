<?php

namespace App\Filament\Widgets;

use App\Models\Balita;
use App\Models\Imunisasi;
use Filament\Widgets\ChartWidget;

class ImunisasiCoverageWidget extends ChartWidget
{
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 'full';
    
    public function getHeading(): ?string
    {
        return 'Cakupan Imunisasi';
    }

    protected function getData(): array
    {
        $totalBalita = Balita::count();
        
        if ($totalBalita === 0) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        // Hitung jumlah balita yang sudah mendapat setiap jenis imunisasi
        $imunisasiData = [
            'HB-0' => Imunisasi::where('jenis_vaksin', 'HB-0')->distinct('balita_id')->count('balita_id'),
            'BCG' => Imunisasi::where('jenis_vaksin', 'BCG')->distinct('balita_id')->count('balita_id'),
            'Polio 1' => Imunisasi::where('jenis_vaksin', 'Polio 1')->distinct('balita_id')->count('balita_id'),
            'DPT-HB-Hib 1' => Imunisasi::where('jenis_vaksin', 'DPT-HB-Hib 1')->distinct('balita_id')->count('balita_id'),
            'Polio 2' => Imunisasi::where('jenis_vaksin', 'Polio 2')->distinct('balita_id')->count('balita_id'),
            'DPT-HB-Hib 2' => Imunisasi::where('jenis_vaksin', 'DPT-HB-Hib 2')->distinct('balita_id')->count('balita_id'),
            'Polio 3' => Imunisasi::where('jenis_vaksin', 'Polio 3')->distinct('balita_id')->count('balita_id'),
            'DPT-HB-Hib 3' => Imunisasi::where('jenis_vaksin', 'DPT-HB-Hib 3')->distinct('balita_id')->count('balita_id'),
            'IPV' => Imunisasi::where('jenis_vaksin', 'IPV')->distinct('balita_id')->count('balita_id'),
            'Campak' => Imunisasi::where('jenis_vaksin', 'Campak')->distinct('balita_id')->count('balita_id'),
        ];

        // Hitung persentase
        $percentages = array_map(fn($count) => round(($count / $totalBalita) * 100, 1), $imunisasiData);

        return [
            'datasets' => [
                [
                    'label' => 'Cakupan (%)',
                    'data' => array_values($percentages),
                    'backgroundColor' => 'rgb(59, 130, 246)',
                    'borderColor' => 'rgb(29, 78, 216)',
                ],
            ],
            'labels' => array_keys($imunisasiData),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'max' => 100,
                    'ticks' => [
                        'callback' => 'function(value) { return value + "%"; }',
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}
