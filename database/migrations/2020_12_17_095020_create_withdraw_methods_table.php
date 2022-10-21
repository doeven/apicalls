<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('img')->nullable();
            $table->string('min');
            $table->string('max');
            $table->decimal('fixed_fee', 13, 2)->default(0);
            $table->integer('perc_fee')->unsigned();
            $table->decimal('exchange', 13, 2)->default(0);
            $table->string('val1')->nullable();
            $table->string('val2')->nullable();
            $table->string('val3')->nullable();
            $table->string('currency')->nullable();
            $table->tinyInteger('status')->unsigned()->default(0);
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
        Schema::dropIfExists('withdraw_methods');
    }
}
