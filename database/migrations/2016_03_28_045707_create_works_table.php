<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('city');
            $table->string('district');
            $table->string('address');
            $table->tinyInteger('need_num')->unsigned();
            $table->tinyInteger('hired_num')->unsigned()->default(0);
            $table->string('pay_amount');
            $table->string('settlement_type');
            $table->string('pay_type');
            $table->string('pay_time');
            $table->boolean('has_commission');
            $table->string('commission')->nullable();
            $table->string('lunch')->nullable();
            $table->string('gender');
            $table->string('contact');
            $table->boolean('need_interview');
            $table->timestamp('interview_time')->nullable();
            $table->string('interview_place')->nullable();
            $table->text('requirements');
            $table->text('description');
            //兼职状态['-1'=>'审核不通过','0'=>'审核中','1'=>'审核通过，接受报名中','2'=>'兼职结束，相互评价中','3'=>'评价完成']
            $table->tinyInteger('status')->default(0);
            $table->text('check_failed_msg')->nullable();
            $table->integer('page_view')->unsigned()->default(0);
            $table->integer('employer_id')->unsigned();//发布兼职的用户id
            $table->timestamps();
            $table->foreign('employer_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('works');
    }
}
