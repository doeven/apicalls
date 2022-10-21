<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePledgeReceiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pledge_receivers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('pack');
            $table->decimal('to_receive', 13, 2)->default(0);
            $table->decimal('received', 13, 2)->default(0);
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
        Schema::dropIfExists('pledge_receivers');
    }
}
