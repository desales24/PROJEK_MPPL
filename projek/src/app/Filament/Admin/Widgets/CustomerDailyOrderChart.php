<?php

namespace App\Filament\Admin\Resources\CustomerDailyOrderChartResource\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CustomerDailyOrderChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Pelanggan per Hari';
    protected int | string | array $columnSpan = 2;

    protected function getData(): array
    {
        // Ambil data jumlah pelanggan unik per hari
        $data = DB::table('orders')
            ->selectRaw('DATE(order_date) as date, COUNT(DISTINCT customer_id) as total_customers')
            ->groupByRaw('DATE(order_date)')
            ->orderBy('date')
            ->get();

        $labels = $data->pluck('date')->toArray();
        $values = $data->pluck('total_customers')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Pelanggan Unik',
                    'data' => $values,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Bisa diganti ke 'line' jika ingin grafik garis
    }
}
