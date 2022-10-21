<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WithdrawMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Adding Withdraw Method Data
        DB::table('withdraw_methods')->insert([
            'name' => 'Skrill',
            'min' => '50',
            'max' => '1000',
            'fixed_fee' => '10',
            'perc_fee' => '2',
            'exchange' => '1',
            'currency' => 'USD',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
