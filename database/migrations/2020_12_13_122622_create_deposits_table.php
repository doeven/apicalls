<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->tinyInteger('gateway_id')->unsigned();
            $table->decimal('amount', 13, 2)->default(0);
            $table->string('status')->default(0);
            $table->string('tx_id');
            $table->string('address')->nullable();
            $table->decimal('btc_amount', 13, 8)->default(0);
            $table->decimal('fee', 13, 2)->default(0);
            $table->string('custom')->nullable();
            $table->string('try')->default(0);
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
        Schema::dropIfExists('deposits');
    }
}
