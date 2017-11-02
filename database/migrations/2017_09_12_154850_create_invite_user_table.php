<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInviteUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invite', function (Blueprint $table) {
            $table->integer('from_id')->unsigned();
            $table->integer('to_id')->unsigned();
            $table->integer('work_id')->unsigned();
            //邀请类型 ['1' => '用户','2' =>'雇主','3' =>'公司']
            $table->tinyInteger('type')->default(1);
            //处理状态 ['0' => '未读','2' =>'已处理']
            $table->tinyInteger('status')->default(0);
            $table->foreign('from_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('to_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('work_id')->references('id')->on('works')->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['from_id','to_id','work_id']);
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
        Schema::drop('invite');
    }
}
