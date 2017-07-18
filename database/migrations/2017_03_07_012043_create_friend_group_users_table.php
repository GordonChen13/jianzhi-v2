<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendGroupUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friend_group_users', function (Blueprint $table) {
            //
            $table->integer('user_id')->unsigned();
            $table->integer('friend_group_id')->unsigned();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('friend_group_id')->references('id')->on('friend_groups')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'friend_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('friend_group_users');
    }
}
