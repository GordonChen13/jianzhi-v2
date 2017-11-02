<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follow', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('from_id')->unsigned();
            $table->integer('to_id')->unsigned();
            //关注状态 [两位数字，前数表示关注人，后数表示被关注人，1、2、3分别代表普通用户、雇主、公司。如21代表雇主关注普通用户]
            $table->tinyInteger('status');
            $table->foreign('from_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('to_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::drop('follow');
    }
}
