<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Customer;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Facades\DB;

class CustomerChart extends BarChartWidget
{
    protected static ?string $heading = 'Jumlah Customer Baru per Bulan';
    protected static ?int $sort = 1; // Tampilkan setelah chart pembayaran

    protected function getData(): array
    {
        $customers = Customer::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = [];

        foreach (range(1, 12) as $month) {
            $labels[] = \Carbon\Carbon::create()->month($month)->locale('id')->monthName;
            $data[] = (int) ($customers->firstWhere('month', $month)->total ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Customer',
                    'data' => $data,
                    'backgroundColor' => '#60a5fa', // biru
                ],
            ],
            'labels' => $labels,
        ];
    }
}
