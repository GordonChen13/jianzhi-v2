<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkReviewKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_review_keywords', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value');
            //type [a,b,c对应综合评分好评，中评，差评，体现该关键词的评分属性]
            $table->char('type');
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
        Schema::dropIfExists('work_review_keywords');
    }
}
