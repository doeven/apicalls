<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Adding Bank Details
        DB::table('banks')->insert([
            'user_id' => 1,
            'acc_num' => '0123456789',
            'acc_name' => 'Admin Account',
            'bank' => 'GTBank',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Adding Bank Details User 2
        DB::table('banks')->insert([
            'user_id' => 2,
            'acc_num' => '9706482130',
            'acc_name' => 'Mark Joseph',
            'bank' => 'Fidelity',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Adding Bank Details User 3
        DB::table('banks')->insert([
            'user_id' => 3,
            'acc_num' => '0123456789',
            'acc_name' => 'John Doe',
            'bank' => 'FCMB',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Adding Bank Details User 4
        DB::table('banks')->insert([
            'user_id' => 4,
            'acc_num' => '4987632145',
            'acc_name' => 'Luke Frank',
            'bank' => 'First Bank',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
