<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkReviewReplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_review_reply', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content');
            $table->integer('review_id')->unsigned();
            $table->integer('employer_id')->unsigned();
            $table->foreign('review_id')->references('id')->on('work_reviews')->onDelete('cascade');
            $table->foreign('employer_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('work_review_reply');
    }
}
