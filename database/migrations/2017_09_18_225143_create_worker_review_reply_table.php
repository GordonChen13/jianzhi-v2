<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkerReviewReplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker_review_reply', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content');
            $table->integer('review_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('review_id')->references('id')->on('worker_reviews')->onDelete('cascade');
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
        Schema::dropIfExists('worker_review_reply');
    }
}
