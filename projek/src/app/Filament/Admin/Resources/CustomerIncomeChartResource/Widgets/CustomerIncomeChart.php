<?php

namespace App\Filament\Admin\Resources\CustomerIncomeChartResource\Widgets;

use Filament\Widgets\ChartWidget;

class CustomerIncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
