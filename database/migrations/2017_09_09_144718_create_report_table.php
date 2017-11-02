<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('work_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('reason_type')->unsigned();
            //['0' => '未处理','1' => '经核实，兼职已删除','2' => '证据不足，举报无效']
            $table->tinyInteger('status')->default(0);
            $table->foreign('work_id')->references('id')->on('works')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('reason_type')->references('type')->on('report_reason')
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
        Schema::table('report', function (Blueprint $table) {
            //
        });
    }
}
