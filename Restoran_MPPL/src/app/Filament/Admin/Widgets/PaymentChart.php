<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Payment;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Facades\DB;

class PaymentChart extends BarChartWidget
{
    protected static ?string $heading = 'Total Pembayaran Bulanan';
    protected static ?int $sort = 0; // Supaya tampil di atas dashboard

    protected function getData(): array
    {
        // Ambil total pembayaran yang sudah "paid", dikelompokkan per bulan
        $payments = Payment::selectRaw('MONTH(paid_at) as month, SUM(amount) as total')
            ->where('status', 'paid')
            ->groupBy(DB::raw('MONTH(paid_at)'))
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = [];

        // Buat label bulan dan datanya
        foreach (range(1, 12) as $month) {
            $labels[] = \Carbon\Carbon::create()->month($month)->locale('id')->monthName;
            $data[] = (float) ($payments->firstWhere('month', $month)->total ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Pembayaran',
                    'data' => $data,
                    'backgroundColor' => '#4ade80', // hijau muda
                ],
            ],
            'labels' => $labels,
        ];
    }
}
