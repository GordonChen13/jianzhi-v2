<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplyWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apply_works', function (Blueprint $table) {
            //
            $table->integer('user_id')->unsigned();
            $table->integer('work_id')->unsigned();
            //申请兼职状态['-2'='面试失败','-1'=>'申请被拒绝','0'=>'申请中，等待审核','1'=>'申请通过等待面试',
            //'2'=>'面试通过，等待工作','3'=>'兼职完成，等待评论','4'=>'评论完成']
            $table->tinyInteger('status');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('work_id')->references('id')->on('works')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['work_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('apply_works');
    }
}
