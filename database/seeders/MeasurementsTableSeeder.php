<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MeasurementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $measurements = [
            'Chest',
            'Waist',
            'Hip',
            'Shoulder Width',
            'Sleeve Length',
            'Neck',
            'Inseam',
            'Outseam',
            'Thigh',
            'Bicep',
            'Cuff',
            'Length',
            'Front Length',
            'Back Length',
            'Torso',
            'Wrist',
            'Calf',
            'Ankle'
        ];

        $now = Carbon::now();

        foreach ($measurements as $measurement) {
            DB::table('measurements')->insert([
                'name' => $measurement,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }
    }
}