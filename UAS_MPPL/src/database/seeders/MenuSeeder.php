<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        Menu::insert([
            ['name' => 'Nasi Goreng', 'price' => 20000, 'description' => 'Nasi goreng lengkap dengan ayam dan telur'],
            ['name' => 'Mie Ayam', 'price' => 15000, 'description' => 'Mie ayam dengan pangsit goreng'],
            ['name' => 'Es Teh Manis', 'price' => 5000, 'description' => 'Minuman teh manis dingin'],
        ]);
    }
}
