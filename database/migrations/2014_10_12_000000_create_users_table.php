<?php

use Illuminate\Support\Facades\Schema;
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
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile')->unique()->nullable();
            $table->string('pic_path')->default('/images/default.png');
            $table->string('cover_path')->default('/images/default01.png');
            $table->integer('age')->unsigned()->nullable();
            $table->string('gender')->nullable();
            $table->string('city')->nullable();
            $table->string('simple_intro')->nullable();
            $table->string('school')->nullable();
            $table->string('major')->nullable();
            $table->text('introduction')->nullable();
            $table->string('password');
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
        Schema::dropIfExists('users');
    }
}
