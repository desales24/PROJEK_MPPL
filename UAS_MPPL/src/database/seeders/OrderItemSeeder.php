<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItem;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        OrderItem::insert([
            ['order_id' => 1, 'menu_id' => 1, 'quantity' => 1, 'subtotal' => 20000],
            ['order_id' => 1, 'menu_id' => 3, 'quantity' => 1, 'subtotal' => 5000],
        ]);
    }
}
