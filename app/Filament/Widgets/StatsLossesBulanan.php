<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Report;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsLossesBulanan extends BaseWidget
{
    protected ?string $heading = 'Losses Bulanan';

    protected function getCards(): array
    {
        $products = Product::take(4)->get();
        $cards = [];

        foreach ($products as $product) {
            $avgSusutBulanan = Report::where('product_id', $product->id)
                ->whereBetween('tanggal', [now()->startOfMonth()->subMonths(1), now()->endOfMonth()])
                ->avg('susut_harian');

            $cards[] = Card::make($product->name, number_format($avgSusutBulanan, 2) . '%')
                ->description('Rata-rata susut bulan ini')
                ->color($this->getColorByValue($avgSusutBulanan));
        }

        return $cards;
    }

    protected function getColorByValue($value): string
    {
        if (is_null($value)) {
            return 'gray'; 
        }
    
        if ($value < 0.5) {
            return 'success';
        } elseif ($value < 1) {
            return 'warning';
        }
    
        return 'danger';
    }
}
