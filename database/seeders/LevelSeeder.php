<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Adding Standard User Level
        DB::table('levels')->insert([
            'id' => 1,
            'name' => 'Standard',
            'description' => 'The Basic user level',
            'min' => '100',
            'max' => '200',
            'amount' => '10000',
            'price' => '1200',
            'status' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
