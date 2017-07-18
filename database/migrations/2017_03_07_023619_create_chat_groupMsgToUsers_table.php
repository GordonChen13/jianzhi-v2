<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatGroupMsgToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_groupMsgToUsers', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->text('content');
            $table->boolean('status');//ture:received; fales:not received
            $table->integer('user_id')->unsigned();//接收群信息的用户Id;
            $table->integer('chat_groupMsgContent_id')->unsigned();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('chat_groupMsgContent_id')->references('id')->on('chat_groupMsgContents')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('chat_groupMsgToUsers');
    }
}
