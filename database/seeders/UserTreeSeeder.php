<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User Tree Addition
        DB::table('user_trees')->insert([
            'user_id' => 1,
            'left_paid' => 0,
            'right_paid' => 0,
            'left_free' => 0,
            'right_free' => 0,
            'left_bv' => 0,
            'right_bv' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // User Tree Addition
        DB::table('user_trees')->insert([
            'user_id' => 2,
            'left_paid' => 0,
            'right_paid' => 0,
            'left_free' => 0,
            'right_free' => 0,
            'left_bv' => 0,
            'right_bv' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_trees')->insert([
            'user_id' => 3,
            'left_paid' => 0,
            'right_paid' => 0,
            'left_free' => 0,
            'right_free' => 0,
            'left_bv' => 0,
            'right_bv' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_trees')->insert([
            'user_id' => 4,
            'left_paid' => 0,
            'right_paid' => 0,
            'left_free' => 0,
            'right_free' => 0,
            'left_bv' => 0,
            'right_bv' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
