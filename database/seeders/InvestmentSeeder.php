<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvestmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add Investment
        DB::table('investments')->insert([
            'user_id' => 1,
            'amount' => 1000,
            'package_id' => 1,
            'receive' => 200,
            'next_due' => now(),
            'run' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('investments')->insert([
            'user_id' => 1,
            'amount' => 500,
            'package_id' => 1,
            'receive' => 100,
            'next_due' => now(),
            'run' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
