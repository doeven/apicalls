<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Welcome Email
        DB::table('email_templates')->insert([
            'title' => 'Account Created Successfully',
            'body' => '<p>Hi %firstname%,</p>
            <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br />Welcome. You are one step away from achieving your financial goals so sit back and watch us make your money work for you.</p>
            <p>We are poised to give you consistent and guaranteed weekly and monthly profits by trading and investing in diversified assets in the global capital market.</p>
            <p>With our simple investment process, we would show you a whole new process to end the rat race.</p>
            <ol>
            <li>Deposit</li>
            <li>Invest</li>
            <li>Withdraw/ Reinvest</li>
            <li>Refer a friend</li>
            </ol>
            <p>Our Affiliate program is our way of appreciating our loyal investors for expanding the team. You with get a 5% referral commission on every deposit from an investor who signs up using your referral link. There are other additional bonuses on different levels.</p>
            <p>Your username is %username%.&nbsp; &nbsp; &nbsp; &nbsp;</p>
            <p>Thanks,<br /></p>',
            'slug' => 'welcome',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        
        // Investment Complete Email
        DB::table('email_templates')->insert([
            'title' => 'Investment Complete',
            'body' => '<p>Hi %firstname%,</p>
            <p>Congratulations, you have earned some money. %amount% has been remitted to your account.</p>
            <p>Your current balance is $%balance%.&nbsp;</p>
            <p>Thanks,</p>
            ',
            'slug' => 'investment-complete',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Deposit Confirmed Email
        DB::table('email_templates')->insert([
            'title' => 'Deposit Confirmed',
            'body' => '<p>Hi %firstname%,</p>
            <p>Congratulations, your deposit is now confirmed.</p>
            <p>%amount% has been added to your account.</p>
            <p>Your current balance is $%balance%.&nbsp;</p>
            <p>&nbsp;</p>
            <p>Thanks,</p>
            ',
            'slug' => 'deposit-confirmed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Withdrawal Successful Email
        DB::table('email_templates')->insert([
            'title' => 'Withdrawal Successfully',
            'body' => '<p>Hi %firstname%,</p>
            <p>Your withdrawal request has been confirmed and processed successfully.</p>
            <p>Please check your bitcoin wallet to find your funds.</p>
            <p>Thanks,<br /></p>',
            'slug' => 'withdrawal-successful',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Investment Activated Email
        DB::table('email_templates')->insert([
            'title' => 'Investment Activated',
            'body' => '<p>Hi %firstname%,</p>
            <p>You have successfully activated the %package% .</p>
            <p>Your investment amount is %amount% and your current balance is %balance%.</p>
            <p>You will make a profit of %profit%</p>
            <p>Thanks,<br /></span></p>',
            'slug' => 'investment-activated',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Login Code Verification Email
        DB::table('email_templates')->insert([
            'title' => 'Login Email Verification',
            'body' => '<p>Hi,</p><p><br></p><p>Your login verification code is:</p>
            <p><br></p><h1><strong>%code%</strong></h1><p><br></p>
            <p>This code expires in 1 min.</p>
            <p><br></p><p>Thanks,</p>',
            'slug' => 'email-login-verify',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        

    }
}
