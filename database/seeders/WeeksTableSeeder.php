<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WeeksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
    {
        $days = [
            'Saturday',
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday'
        ];

        foreach ($days as $day) {
            DB::table('weeks')->insert([
                'day' => $day,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
