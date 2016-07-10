<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->increments('id');

            // user
            $table->integer('user_id')->unsigned();

            // operation
            $table->integer('type')->unsigned();

            // previous value
            $table->text('text');

            // a reply many belongs to question or answers
            $table->integer('for_item_id')->unsigned();
            $table->string('for_item_type');

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
        Schema::drop('histories');
    }
}
