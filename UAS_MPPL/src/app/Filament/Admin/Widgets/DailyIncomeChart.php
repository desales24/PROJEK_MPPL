<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DailyIncomeChart extends ChartWidget
{
    protected int | string | array $columnSpan = '1';
    // protected int | string | array $height = '300px';

    protected function getData(): array
    {
        $startDate = Carbon::now()->startOfDay();            // Hari ini
        $endDate = Carbon::now()->addDays(6)->endOfDay();    // 6 hari ke depan

        // Ambil data total pemasukan berdasarkan tanggal
        $data = DB::table('payments')
            ->selectRaw('DATE(paid_at) as date, SUM(amount) as total')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = collect();
        $totals = collect();

        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->addDays($i)->format('Y-m-d');
            $label = Carbon::parse($date)->format('d M'); // Contoh: "19 Jun"

            $labels->push($label);

            $dailyTotal = $data->firstWhere('date', $date)->total ?? 0;
            $totals->push($dailyTotal);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => $totals,
                    'backgroundColor' => 'rgba(255, 159, 64, 0.6)',
                    'borderColor' => 'rgba(255, 159, 64, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Bisa diganti dengan 'line' jika ingin garis
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'title' => [
                    'display' => true,
                    'text' => 'Pemasukan',
                    'align' => 'center',
                    'font' => [
                        'size' => 18,
                        'weight' => 'bold',
                    ],
                    'padding' => [
                        'top' => 10,
                        'bottom' => 10,
                    ],
                ],
            ],
        ];
    }
}
