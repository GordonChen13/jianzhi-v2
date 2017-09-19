<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('treat_star');//待遇评分
            $table->tinyInteger('pay_speed');//工资发放速度评分
            $table->tinyInteger('description_match');//描述相符打分
            $table->text('content')->nullable();
            $table->string('pic_path')->nullable();
            $table->integer('work_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('employer_id')->unsigned();
            $table->timestamps();
            $table->foreign('work_id')->references('id')->on('works')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('employer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_reviews');
    }
}
