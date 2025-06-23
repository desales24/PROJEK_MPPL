<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class CustomerChart extends ChartWidget
{
    protected static ?string $heading = 'Customer Baru 7 Hari Terakhir';

    protected function getData(): array
    {
        $dates = collect(range(0, 6))->map(fn ($i) => now()->subDays(6 - $i)->format('Y-m-d'));

        $customers = User::whereDate('created_at', '>=', now()->subDays(6))
            ->get()
            ->groupBy(fn ($item) => $item->created_at->format('Y-m-d'));

        $labels = [];
        $values = [];

        foreach ($dates as $date) {
            $labels[] = Carbon::parse($date)->translatedFormat('d M');
            $values[] = ($customers[$date] ?? collect())->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Customer Baru',
                    'data' => $values,
                    'backgroundColor' => '#60a5fa',
                    'borderColor' => '#2563eb',
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
