<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('fname');
            $table->string('lname');
            $table->string('email')->unique();
            $table->string('password');
            $table->tinyInteger('twofa_type')->unsigned()->default(0);
            $table->decimal('balance', 13, 2)->default(0);
            $table->integer('parent')->default(0);
            $table->enum('position', ['L', 'R']);
            $table->integer('referrer')->default(0);
            $table->tinyInteger('kyc')->unsigned()->default(0);
            $table->tinyInteger('paid_status')->unsigned()->default(1);
            $table->timestamp('act_time')->nullable();
            $table->tinyInteger('banned')->unsigned()->default(0);
            $table->tinyInteger('ver_status')->unsigned()->default(1);
            $table->string('roles')->nullable();
            $table->string('mobile')->nullable();
            $table->date('dob')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('post_code')->nullable();
            $table->string('country')->nullable();
            $table->string('tracked_country')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->integer('notif')->unsigned()->default(0);
            $table->tinyInteger('alert')->unsigned()->default(0);
            $table->string('btc_address')->nullable();
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->text('profile_photo_path')->nullable();
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
        Schema::dropIfExists('users');
    }
}
