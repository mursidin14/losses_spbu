<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LossesPenerimaan extends BaseWidget
{
    public static function canView(): bool
    {
        return auth()->user()->hasRole('admin') || auth()->user()->hasRole('supervisor');
    }

    protected ?string $heading = 'Losses Penerimaan Harian';

    protected function getCards(): array
    {
        $products = Product::whereIn('id', [1, 2, 3, 4])->get();
        $cards = [];

        foreach ($products as $product) {
            $latestReport = $product->penerimaan()->latest('tanggal')->first();

            $losses = $latestReport?->susut_tangki ?? 0;
            $tanggal = $latestReport?->tanggal?->format('d M Y') ?? 'N/A';

            $cards[] = Stat::make($product->name, number_format($losses))
                ->description("Data terakhir: {$tanggal}")
                ->color($this->getColorByValue($losses))
                ->icon('heroicon-o-chart-bar', IconPosition::Before)
                ->url(route('filament.admin.resources.penerimaans.index'), shouldOpenInNewTab: false);
        }

        return $cards;
    }

    protected function getColorByValue(float $value): string
    {
        if ($value < 0.5) {
            return 'success';
        } elseif ($value < 1) {
            return 'warning';
        }
        return 'danger';
    }
}
