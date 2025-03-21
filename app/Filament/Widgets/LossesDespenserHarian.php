<?php

namespace App\Filament\Widgets;

use App\Models\Despenser;
use App\Models\Product;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class LossesDespenserHarian extends ChartWidget
{
    protected static ?string $heading = 'Operator Losses';

    protected function getData(): array
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // Ambil semua tanggal dalam bulan ini
        $dates = [];
        for ($date = $startOfMonth; $date <= $endOfMonth; $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        $productColors = [
            'Pertalite' => '#10b981',
            'Pertamax' => '#f59e0b',
            'Dexlite' => '#3b82f6',  
            'Solar' => '#ef4444',   
        ];

        $datasets = [];

        $products = Product::all();

        foreach ($products as $product) {
            $color = $productColors[$product->name] ?? '#6366f1';

            foreach ([1, 2] as $shift) {
                $losses = [];

                foreach ($dates as $date) {
                    $totalLoss = Despenser::whereDate('tanggal', $date)
                        ->where('shift_id', $shift)
                        ->where('product_id', $product->id)
                        ->sum('susut_harian');

                    $losses[] = round($totalLoss, 2);
                }

                $datasets[] = [
                    'label' => "{$product->name} Shift {$shift}",
                    'data' => $losses,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'borderWidth' => 1,
                ];
            }
        }

        return [
            'labels' => $dates,
            'datasets' => $datasets,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
