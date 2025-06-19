<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Menu;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        $availableMenus = Menu::where('available', true)->count();

        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->addDays(6)->endOfDay();

        $totalIncome = DB::table('payments')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->sum('amount');

        return [
            Stat::make('Menu Tersedia', $availableMenus)
                ->description('Total menu yang tersedia saat ini')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('success'),

            Stat::make('Pemasukan 7 Hari ke Depan', 'Rp ' . number_format($totalIncome, 0, ',', '.'))
                ->description('Total dari hari ini sampai 6 hari ke depan')
                ->icon('heroicon-o-calendar-days')
                ->color('primary'),
        ];
    }
}
