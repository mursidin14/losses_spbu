<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Report;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SusutMingguan extends BaseWidget
{

    public static function canView(): bool
    {
        return auth()->user()->hasRole('admin') || auth()->user()->hasRole('supervisor');
    }

    protected ?string $heading = 'Losses Mingguan';

    protected function getCards(): array
    {
        $products = Product::take(4)->get();
        $cards = [];

        foreach ($products as $product) {
            $currentWeek = now()->format('o-W');
            $weeklyReports = Report::where('product_id', $product->id)
                ->whereRaw("DATE_FORMAT(tanggal, '%o-%u') = ?", [$currentWeek])
                ->avg('susut_mingguan');

            $cards[] = Card::make($product->name, number_format($weeklyReports, 2) . '%')
                ->description('Rata-rata susut minggu ini')
                ->color($this->getColorByValue($weeklyReports));
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
