<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class P2PRulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add P2P Settings
        DB::table('p2p_rules')->insert([
            'nplbn_time' => 24,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
