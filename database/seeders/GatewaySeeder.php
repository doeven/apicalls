<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert Some Gateways
        DB::table('gateways')->insert([
            'name' => 'Block.IO',
            'min' => '10',
            'max' => '1000',
            'fixed_fee' => '10',
            'perc_fee' => '2',
            'exchange' => '1',
            'currency' => 'USD',
            'val1' => 'random_string',
            'val2' => 'random_string',
            'val3' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('gateways')->insert([
            'name' => 'PayPal',
            'min' => '50',
            'max' => '1000',
            'fixed_fee' => '10',
            'perc_fee' => '2',
            'exchange' => '1',
            'currency' => 'USD',
            'val1' => 'random_string',
            'val2' => 'random_string',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('gateways')->insert([
            'name' => 'CoinPayments',
            'min' => '50',
            'max' => '1000',
            'fixed_fee' => '0',
            'perc_fee' => '0',
            'exchange' => '1',
            'currency' => 'USD',
            'val1' => 'random_string',
            'val2' => 'random_string',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('gateways')->insert([
            'name' => 'AlfaCoins',
            'min' => '50',
            'max' => '1000',
            'fixed_fee' => '0',
            'perc_fee' => '0',
            'exchange' => '1',
            'currency' => 'USD',
            'val1' => 'secret_key',
            'val2' => 'md5_hashed_password',
            'val3' => 'app_name',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('gateways')->insert([
            'name' => 'CoinGate',
            'min' => '50',
            'max' => '100000',
            'fixed_fee' => '0',
            'perc_fee' => '0',
            'exchange' => '1',
            'currency' => 'USD',
            'val1' => 'api_key',
            'val2' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
