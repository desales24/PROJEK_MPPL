<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Admin\Widgets\IncomeChart;
use App\Filament\Admin\Widgets\CustomerChart;

class Dashboard extends BaseDashboard
{
    protected function getHeaderWidgets(): array
    {
        return [
            IncomeChart::class,
            CustomerChart::class,
        ];
    }
}
