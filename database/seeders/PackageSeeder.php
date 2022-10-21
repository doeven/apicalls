<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add a Package
        DB::table('packages')->insert([
            'title' => 'Package One',
            'description' => 'The first package with name Package One',
            'min' => 100,
            'max' => 400,
            'percent' => 3,
            'run' => 1,
            'time_hours' => 240,
            'bonus' => 0,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
