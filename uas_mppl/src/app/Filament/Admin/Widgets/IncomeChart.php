<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;

class IncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Pemasukan 7 Hari Terakhir';

    protected function getData(): array
    {
        $payments = Payment::where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan (Rp)',
                    'data' => $payments->pluck('total'),
                    'borderColor' => '#4f46e5',
                    'backgroundColor' => '#4f46e580',
                ],
            ],
            'labels' => $payments->pluck('date')->map(fn ($d) => \Carbon\Carbon::parse($d)->format('d M')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
