<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hits', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('day')->unsigned()->default(0);
            $table->integer('week')->unsigned()->default(0);
            $table->integer('month')->unsigned()->default(0);
            $table->integer('total')->unsigned()->default(0);
            $table->string('owner_type');
            $table->integer('owner_id');

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
        Schema::drop('hits');
    }
}
