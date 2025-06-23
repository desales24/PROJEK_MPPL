<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        Menu::insert([
            ['name' => 'Nasi Goreng', 'description' => 'Nasi goreng spesial', 'price' => 20000, 'category' => 'food'],
            ['name' => 'Es Teh Manis', 'description' => 'Minuman dingin', 'price' => 5000, 'category' => 'drink'],
            ['name' => 'Brownies', 'description' => 'Coklat lezat', 'price' => 15000, 'category' => 'dessert'],
        ]);
    }
}
