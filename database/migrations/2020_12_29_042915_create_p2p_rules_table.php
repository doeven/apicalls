<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateP2pRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p2p_rules', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('nplbn')->unsigned()->default(0);
            $table->string('nplbn_time')->default(24);
            $table->tinyInteger('ref_deep_bonus')->unsigned()->default(1);
            $table->tinyInteger('guiders_bonus')->unsigned()->default(1);
            $table->string('currency')->default('USD');
            $table->string('symbol')->default('$');
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
        Schema::dropIfExists('p2p_rules');
    }
}
