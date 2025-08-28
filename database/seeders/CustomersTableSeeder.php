<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $customers = [
            [
                'image_path' => 'customer1.jpg',
                'name' => 'Rahul Sharma',
                'mobile' => '9876543210',
                'alt_mobile' => '9123456780',
                'city' => 'Delhi',
                'reference' => 'Referred by Amit',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'image_path' => 'customer2.jpg',
                'name' => 'Priya Verma',
                'mobile' => '9898989898',
                'alt_mobile' => '9765432109',
                'city' => 'Mumbai',
                'reference' => 'Walk-in',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'image_path' => 'customer3.jpg',
                'name' => 'Arjun Singh',
                'mobile' => '9911223344',
                'alt_mobile' => null,
                'city' => 'Bangalore',
                'reference' => 'Google Ads',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'image_path' => 'customer4.jpg',
                'name' => 'Sneha Kapoor',
                'mobile' => '9090909090',
                'alt_mobile' => '9812345678',
                'city' => 'Jaipur',
                'reference' => 'Friend',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'image_path' => 'customer5.jpg',
                'name' => 'Mohit Gupta',
                'mobile' => '9001122334',
                'alt_mobile' => null,
                'city' => 'Chandigarh',
                'reference' => 'Instagram',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('customers')->insert($customers);
    }
}
