<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class IncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Pemasukan 7 Hari Terakhir';

    protected function getData(): array
    {
        $dates = collect(range(0, 6))->map(fn ($i) => now()->subDays(6 - $i)->format('Y-m-d'));

        $payments = Payment::whereDate('created_at', '>=', now()->subDays(6))
            ->where('status', 'lunas')
            ->get()
            ->groupBy(fn ($item) => $item->created_at->format('Y-m-d'));

        $labels = [];
        $values = [];

        foreach ($dates as $date) {
            $labels[] = Carbon::parse($date)->translatedFormat('d M');
            $values[] = ($payments[$date] ?? collect())->sum('amount');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Pemasukan',
                    'data' => $values,
                    'backgroundColor' => '#4ade80',
                    'borderColor' => '#16a34a',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getHeight(): string|int
    {
        return 300;
    }

    public function getColumnSpan(): int|string|array
    {
        return 1;
    }
}
