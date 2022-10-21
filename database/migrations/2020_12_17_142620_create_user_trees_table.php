<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_trees', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('left_paid');
            $table->integer('right_paid');
            $table->integer('left_free');
            $table->integer('right_free');
            $table->integer('left_bv');
            $table->integer('right_bv');
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
        Schema::dropIfExists('user_trees');
    }
}
