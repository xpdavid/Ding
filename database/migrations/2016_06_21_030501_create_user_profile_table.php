<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('profile_pic_name', 255);
            $table->enum('sex', ['secret', 'male', 'femail'])->default('secret');
            $table->boolean('facebook')->default(false); // display facebook link
            $table->boolean('email')->default(false); // display email link
            $table->string('bio', 50); // restrict length of bio to 50 characters
            $table->text('description'); // self introduction
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
        Schema::drop('user_profiles');
    }
}
