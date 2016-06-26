<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            // User basic information
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            // custom user information
            $table->string('bio')->nullable();
            $table->string('self_intro')->nullable();
            $table->enum('sex', ['Male', 'Female', 'Secret'])->default('Secret');
            $table->string('url_name');

            $table->rememberToken();
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
        Schema::drop('users');
    }
}
