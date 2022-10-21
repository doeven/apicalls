<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            DonationPackSeeder::class,
            EmailSeeder::class,
            GatewaySeeder::class,
            InvestmentSeeder::class,
            LevelSeeder::class,
            P2PRulesSeeder::class,
            PackageSeeder::class,
            SettingsSeeder::class,
            UserBankSeeder::class,
            UserLevelSeeder::class,
            UserSeeder::class,
            UserTreeSeeder::class,
            WithdrawMethodSeeder::class,
        ]);

    }
}