<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_tag', function (Blueprint $table) {
            $table->integer('work_id')->unsigned();
            $table->integer('tag_id')->unsigned();
            $table->foreign('work_id')->references('id')->on('works')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['work_id', 'tag_id']);
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
        Schema::drop('work_tag');
    }
}
