<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::insert([
            ['name' => 'Andi', 'phone' => '08123456789', 'email' => 'andi@email.com'],
            ['name' => 'Budi', 'phone' => '08987654321', 'email' => 'budi@email.com'],
        ]);
    }
}
