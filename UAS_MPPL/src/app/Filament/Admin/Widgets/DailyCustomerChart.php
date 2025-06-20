<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DailyCustomerChart extends ChartWidget
{
    protected int | string | array $columnSpan = '1';
    // protected int | string | array $height = '300px';

    protected static ?string $heading = 'Customer Baru (7 Hari ke Depan)';

    protected function getData(): array
    {
        $labels = collect();
        $totals = collect();

        // Loop hari ini + 6 hari ke depan
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->addDays($i)->format('Y-m-d');
            $label = Carbon::parse($date)->format('d M');

            $count = DB::table('customers')
                ->whereDate('created_at', $date)
                ->count();

            $labels->push($label);
            $totals->push($count);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Customer Baru',
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
        return 'bar';
    }
}
