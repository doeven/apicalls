<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('tagline');
            $table->string('email');
            $table->string('mobile');
            $table->string('address');
            $table->tinyInteger('email_toggle')->unsigned()->default(1);
            $table->tinyInteger('sms_toggle')->unsigned()->default(0);
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->string('about_text')->nullable();
            $table->string('logo')->default('https://full-path-to-logo.png');
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('pinterest')->nullable();
            $table->string('currency')->default('USD');
            $table->string('symbol')->default('$');
            $table->string('theme')->nullable();
            $table->string('color_prim')->nullable()->default('#f5f6fa');
            $table->string('color_sec')->nullable();
            $table->string('footer_credit')->nullable();
            $table->string('footer_desc')->nullable();
            $table->tinyInteger('user_level_bonus')->default(1);
            $table->tinyInteger('ref_deep_bonus')->default(1);
            $table->tinyInteger('paid_act')->unsigned()->default(0);
            $table->tinyInteger('p2p')->unsigned()->default(0);
            $table->tinyInteger('p2p_ex')->unsigned()->default(0);
            $table->string('min_wd')->default(200);
            $table->tinyInteger('dp')->default(1);
            $table->tinyInteger('rinv')->default(0); //ReInvest Switch
            $table->string('license')->default('NjA5MzkyNjFlN2Nh')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
