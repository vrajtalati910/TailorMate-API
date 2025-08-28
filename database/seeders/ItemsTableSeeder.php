<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $items = [
            ['name' => 'Shirt', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pant', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kurta', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Blazer', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Saree', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Jacket', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'T-shirt', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Coat', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lehenga', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Salwar', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('items')->insert($items);
    }
}
