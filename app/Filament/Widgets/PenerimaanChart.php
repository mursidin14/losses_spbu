<?php

namespace App\Filament\Widgets;

use App\Models\Penerimaan;
use App\Models\Product;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class PenerimaanChart extends ChartWidget
{
    protected static ?string $heading = 'Losses Penerimaan';

    protected function getData(): array
    {
        $now = now();
            
        $productIds = [1, 2, 3, 4];
        $products = Product::whereIn('id', $productIds)->get();
    
        $labels = []; 
        $datasets = [];
    
        foreach ($products as $product) {
            $reports = $product->penerimaan()
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->orderBy('tanggal')
            ->get();
    
            $productLabels = $reports->pluck('tanggal')->map(fn($tanggal) => \Carbon\Carbon::parse($tanggal)->format('Y-m-d'))->toArray();
            $susutData = $reports->pluck('susut_harian')->toArray();
    
            $labels = array_unique(array_merge($labels, $productLabels));
    
            $datasets[] = [
                'label' => $product->name,
                'data' => array_values($susutData),
                'fill' => false,
                'borderColor' => ['#f59e0b', '#10b981', '#3b82f6', '#ef4444'],
            ];
        }   

        sort($labels);      
        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
