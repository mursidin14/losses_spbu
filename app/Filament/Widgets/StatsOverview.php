<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Report;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected ?string $heading = 'Losses Tahunan';

    protected function getStats(): array
    {
        $products = Product::all();
        $stats = [];

        foreach ($products as $product) {
            $latestReport = Report::where('product_id', $product->id)
                ->orderByDesc('created_at') 
                ->first();

            $susutTahunan = $latestReport ? round($latestReport->susut_tahunan, 2) : 0;

            $stats[] = Stat::make("{$product->name}", "{$susutTahunan}%")
                ->description('Susut Tahunan')
                ->descriptionIcon('heroicon-m-chart-bar');
        }

        return $stats;
    }
}
