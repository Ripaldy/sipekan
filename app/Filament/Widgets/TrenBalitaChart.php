<?php

namespace App\Filament\Widgets;

use App\Models\Balita;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TrenBalitaChart extends ChartWidget
{
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];
    
    public function getHeading(): ?string
    {
        return 'Tren Jumlah Anak Terdaftar';
    }

    protected function getData(): array
    {
        // Ambil data 12 bulan terakhir
        $data = [];
        $labels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M');
            
            // Hitung total balita yang terdaftar sampai bulan tersebut
            $count = Balita::whereYear('created_at', '<=', $date->year)
                ->where(function ($query) use ($date) {
                    $query->whereYear('created_at', '<', $date->year)
                        ->orWhere(function ($q) use ($date) {
                            $q->whereYear('created_at', '=', $date->year)
                              ->whereMonth('created_at', '<=', $date->month);
                        });
                })
                ->count();
            
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Anak',
                    'data' => $data,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
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
