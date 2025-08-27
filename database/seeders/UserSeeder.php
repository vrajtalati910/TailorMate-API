<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@tailormate.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // change in production
                'role' => User::ADMIN,
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@tailormate.com'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('password'), // change in production
                'role' => User::STAFF,
            ]
        );
    }
}
