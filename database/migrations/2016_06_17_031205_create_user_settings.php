<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   // 2 for everyone
        // 1 for only people I subscribed to
        // 0 for no one
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->text('personal_domain');
            $table->boolean('personal_domain_modified')->default(false);
            $table->integer('receiving_messages')->unsigned()->default(2);
            $table->boolean('email_messages')->default(true);
            $table->integer('receiving_invitations')->unsigned()->default(2);
            $table->boolean('email_invitations')->default(true);
            $table->integer('receiving_updates')->unsigned()->default(2);
            $table->boolean('email_updates')->default(true);
            $table->integer('receiving_replies')->unsigned()->default(2);
            $table->boolean('email_replies')->default(true);
            $table->integer('receiving_votings')->unsigned()->default(2);
            $table->boolean('email_votings')->default(true);
            $table->integer('receiving_reply_votings')->unsigned()->default(2);
            $table->boolean('email_reply_votings')->default(true);
            $table->integer('receiving_subscriptions')->unsigned()->default(2);
            $table->boolean('email_subscriptions')->default(true);

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
        Schema::drop('settings');
    }
}
