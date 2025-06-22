<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\HasWidgets;
use App\Filament\Admin\Widgets\PaymentChart;
use App\Filament\Admin\Widgets\CustomerChart;

class Dashboard extends BaseDashboard
{
    use HasWidgets;

    protected static string $view = 'filament.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            PaymentChart::class,
            CustomerChart::class,
        ];
    }
}
