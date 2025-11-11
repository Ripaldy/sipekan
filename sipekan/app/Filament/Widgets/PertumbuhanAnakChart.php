<?php

namespace App\Filament\Widgets;

use App\Models\Pengukuran;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PertumbuhanAnakChart extends ChartWidget
{
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 'full';
    
    public ?string $filter = '2025';
    
    public function getHeading(): ?string
    {
        return 'Rata-rata Pertumbuhan Anak Per Bulan';
    }
    
    protected function getFilters(): ?array
    {
        return [
            '2025' => '2025',
            '2024' => '2024',
            '2023' => '2023',
        ];
    }

    protected function getData(): array
    {
        $year = $this->filter ?? now()->year;
        
        $beratBadanData = [];
        $tinggiBadanData = [];
        $labels = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $labels[] = date('M', mktime(0, 0, 0, $month, 1));
            
            // Rata-rata berat badan per bulan
            $avgBerat = Pengukuran::whereYear('tanggal_ukur', $year)
                ->whereMonth('tanggal_ukur', $month)
                ->avg('berat_badan');
            
            // Rata-rata tinggi badan per bulan (dalam cm, perlu dibagi 10 untuk scale)
            $avgTinggi = Pengukuran::whereYear('tanggal_ukur', $year)
                ->whereMonth('tanggal_ukur', $month)
                ->avg('tinggi_badan');
            
            $beratBadanData[] = $avgBerat ? round($avgBerat, 1) : 0;
            $tinggiBadanData[] = $avgTinggi ? round($avgTinggi / 10, 1) : 0; // Dibagi 10 untuk scale yang sesuai
        }

        return [
            'datasets' => [
                [
                    'label' => 'Berat Badan (kg)',
                    'data' => $beratBadanData,
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'tension' => 0.4,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Tinggi Badan (cm/10)',
                    'data' => $tinggiBadanData,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'tension' => 0.4,
                    'yAxisID' => 'y',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
    
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 25,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
