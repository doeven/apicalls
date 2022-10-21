<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Adding First Test User
        DB::table('users')->insert([
            'username' => 'admin',
            'fname' => 'Admin',
            'lname' => 'Account',
            'email' => 'admin@admin.com',
            'balance' => 1000,
            'parent' => 0,
            'referrer' => 0,
            'mobile' => '970-279-9137',
            'password' => Hash::make('adminadmin'),
            'roles' => '["ROLE_ADMIN", "ROLE_MANAGEMENT", "ROLE_FINANCE", "ROLE_ACCOUNT_MANAGER", "ROLE_SUPPORT"]',
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        // Adding Second Test User
        DB::table('users')->insert([
            'username' => 'mark',
            'fname' => 'Mark',
            'lname' => 'Joseph',
            'email' => 'joseph@admin.com',
            'balance' => 300,
            'parent' => 1,
            'referrer' => 1,
            'mobile' => '773-406-8347',
            'password' => Hash::make('adminadmin'),
            'roles' => '["ROLE_MANAGEMENT", "ROLE_FINANCE", "ROLE_ACCOUNT_MANAGER", "ROLE_SUPPORT"]',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Adding Third Test User
        DB::table('users')->insert([
            'username' => 'john',
            'fname' => 'John',
            'lname' => 'Doe',
            'email' => 'john@admin.com',
            'balance' => 500,
            'parent' => 2,
            'position' => 'R',
            'referrer' => 2,
            'mobile' => '858-761-3863',
            'password' => Hash::make('adminadmin'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Adding Fourth Test User
        DB::table('users')->insert([
            'username' => 'luke',
            'fname' => 'Luke',
            'lname' => 'Frank',
            'email' => 'luke@admin.com',
            'balance' => 800,
            'parent' => 2,
            'referrer' => 3,
            'mobile' => '407-439-2172',
            'password' => Hash::make('adminadmin'),
            'roles' => '["ROLE_SUPPORT"]',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
