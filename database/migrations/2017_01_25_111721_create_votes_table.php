<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('larapoll_votes', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('user_id');
            $table->integer('option_id')->unsigned();
            $table->timestamps();

            $table->foreign('option_id')->references('id')->on('larapoll_options');
//            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('larapoll_votes');
    }
}
