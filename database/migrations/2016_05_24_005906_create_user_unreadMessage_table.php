<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserUnreadMessageTable extends Migration
{
    /**
     * Run the migrations.
     * This is an intermediate table to established eloquent relationship between user and unreadmessage
     * @return void
     */
    public function up()
    {
        Schema::create('unreadMessage_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('message_id');
            $table->integer('user_id');
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
        Schema::drop('unreadMessage_user');
    }
}
