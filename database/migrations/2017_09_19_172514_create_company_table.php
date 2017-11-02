<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slogan')->nullable();
            $table->string('industry')->nullable();
            $table->string('size')->nullable();
            $table->string('city')->nullable();
            $table->text('intro')->nullable();
            $table->string('logo_path')->default('images/defaultlogo.png');
            $table->string('cover_path')->default('images/defaultcover01.png');
            $table->string('licence_path');
            $table->text('picture_path')->nullable();
            //[certificated: 0 => 未审核，1 => 审核通过]
            $table->tinyInteger('certificated')->default(0);
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
        Schema::dropIfExists('company');
    }
}
