<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        Table::insert([
            ['table_number' => 'T01', 'capacity' => 4],
            ['table_number' => 'T02', 'capacity' => 2],
            ['table_number' => 'T03', 'capacity' => 6],
        ]);
    }
}
