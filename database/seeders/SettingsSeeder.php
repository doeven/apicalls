<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Site Settings Default
        DB::table('settings')->insert([
            'title' => 'Laravel API App',
            'tagline' => 'Best Laravel Mod',
            'email' => 'info@example.com',
            'mobile' => '+1900 123 456 789',
            'address' => 'Plot 123 James Catty, Katty, Texas, USA',
            'email_toggle' => 1,
            'sms_toggle' => 1,
            'status' => 1,
            'about_text' => 'We are the best laravel app to use right now',
            'logo' => 'logo.png',
            'facebook' => 'https://www.facebook.com/facebook',
            'twitter' => 'https://www.twitter.com/twitter',
            'linkedin' => 'https://www.linkedin.com/linkedin',
            'instagram' => 'https://www.instagram.com/instagram',
            'youtube' => 'https://www.youtube.com/youtube',
            'pinterest' => 'https://www.pinterest.com/pinterest',
            'currency' => 'USD',
            'symbol' => '$',
            'theme' => 'Color Blend',
            'color_prim' => '#FF0000',
            'color_sec' => '#00FFF0',
            'footer_credit' => '&copy; 2020 Copyright',
            'footer_desc' => 'Best laravel app footer',
            'user_level_bonus' => 1,
            'ref_deep_bonus' => 1,
            'paid_act' => 1,
            'p2p' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
