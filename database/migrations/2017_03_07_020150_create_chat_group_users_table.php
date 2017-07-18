<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatGroupUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_group_users', function (Blueprint $table) {
            //
            $table->integer('user_id')->unsigned();
            $table->integer('chat_group_id')->unsigned();
            $table->string('nick_name');

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('chat_group_id')->references('id')->on('chat_groups')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'chat_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('chat_group_users');
    }
}
