<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Report;
use Filament\Widgets\ChartWidget;

class SusutBulananChart extends ChartWidget
{
    public static function canView(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    protected static ?string $heading = 'Losses Bulanan';
    protected static string $color = 'info';

    protected function getData(): array
    {
        $products = Product::take(4)->get();
        $labels = [];

        $datasets = [];

        foreach ($products as $product) {
            $monthlyReports = Report::selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as bulan, AVG(susut_harian) as avg_susut')
                ->where('product_id', $product->id)
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();

            $data = [];
            foreach ($monthlyReports as $report) {
                if (!in_array($report->bulan, $labels)) {
                    $labels[] = $report->bulan;
                }
                $data[$report->bulan] = round($report->avg_susut, 2);
            }

            $sortedData = [];
            foreach ($labels as $bulan) {
                $sortedData[] = $data[$bulan] ?? 0;
            }

            $datasets[] = [
                'label' => $product->name,
                'data' => $sortedData,
                'borderColor' => '#' . substr(md5($product->id), 0, 6),
                'fill' => false,
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
