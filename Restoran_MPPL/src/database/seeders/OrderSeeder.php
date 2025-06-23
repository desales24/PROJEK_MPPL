<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        Order::create([
            'customer_id' => 1,
            'status' => 'preparing',
        ]);

        Order::create([
            'customer_id' => 2,
            'status' => 'preparing',
        ]);
    }
}
