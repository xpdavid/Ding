<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');

//            1.	{{User}} invite you to answer question {{Question}}
//            2.	{{User}} answer the question {{Question}}
//            3.	{{User}} @ you in his/her {{answer}}
//            4.	{{User}} @ you in his/her {{question}}
//            5.	{{User}} @ you in his/her {{reply}}
//            6.	{{User}} reply your {{Reply}}
//            7.	{{User}} vote up your answers {{answer}}
//            8.	Someone vote down your answers {{answer}}
//            9.	{{User}} vote up your reply {{Reply}}
//            10.	{{User}} subscribe you.
//            11.   {{User}} send message to you


            $table->integer('type')->unsigned();
            
            $table->integer('subject_id')->unsigned();
            $table->integer('object_id')->unsigned();

            $table->boolean('has_read');

            $table->integer('user_id')->unsigned();

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
        Schema::drop('notifications');
    }
}
