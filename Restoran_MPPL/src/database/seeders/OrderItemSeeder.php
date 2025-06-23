<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItem;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        OrderItem::insert([
            ['order_id' => 1, 'menu_id' => 1, 'quantity' => 2],
            ['order_id' => 1, 'menu_id' => 2, 'quantity' => 1],
            ['order_id' => 2, 'menu_id' => 3, 'quantity' => 3],
        ]);
    }
}
