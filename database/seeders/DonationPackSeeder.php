<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DonationPackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's Create a Donation Pack
        DB::table('donation_packs')->insert([
            'title' => 'Basic',
            'description' => 'First Package',
            'min' => 1,
            'max' => 100,
            'percent' => 20,
            'time_days' => 2,
            'bonus' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
