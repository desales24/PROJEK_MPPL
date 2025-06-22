<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Order;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::take(5)->get(); // ambil 5 order pertama

        foreach ($orders as $order) {
            Payment::create([
                'order_id' => $order->id,
                'method' => 'cash',
                'amount' => rand(50000, 150000),
                'status' => 'paid',
                'paid_at' => Carbon::now()->subDays(rand(1, 10)),
            ]);
        }
    }
}
