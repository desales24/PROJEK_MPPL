<?php

// app/Filament/Admin/Widgets/CustomerChart.php
namespace App\Filament\Admin\Widgets;

use App\Models\User as Customer;
use Filament\Widgets\ChartWidget;

class CustomerChart extends ChartWidget
{
    protected static ?string $heading = 'Pertumbuhan Customer';

    protected function getData(): array
    {
        $customers = Customer::where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = $customers->map(function ($item) {
            return date('M Y', mktime(0, 0, 0, $item->month, 1, $item->year));
        });

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Customer',
                    'data' => $customers->pluck('count'),
                    'backgroundColor' => '#10b981',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}