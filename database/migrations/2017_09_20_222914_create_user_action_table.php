<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserActionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_action', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('to_id')->unsigned();
            //用户动态类型['ue1' => '关注商家','uu1' => '关注用户','uw1' => '申请兼职','uw2' => '收藏兼职','uw3' => '评价兼职','uw4' => '分享兼职', 'uq1' => '提出咨询','ur1' =>'回复评价']
            $table->string('type');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('user_action');
    }
}
