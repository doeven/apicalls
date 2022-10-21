<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User Level Addition
        DB::table('user_levels')->insert([
            'user_id' => 1,
            'level' => 1,
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // User Level Addition
        DB::table('user_levels')->insert([
            'user_id' => 2,
            'level' => 1,
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // User Level Addition
        DB::table('user_levels')->insert([
            'user_id' => 3,
            'level' => 1,
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // User Level Addition
        DB::table('user_levels')->insert([
            'user_id' => 4,
            'level' => 1,
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
