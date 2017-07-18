<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkSkillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_skill', function (Blueprint $table) {
            $table->integer('work_id')->unsigned();
            $table->integer('skill_id')->unsigned();

            $table->foreign('work_id')->references('id')->on('works')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('skills')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['work_id', 'skill_id']);
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
        Schema::drop('work_skill');
    }
}
