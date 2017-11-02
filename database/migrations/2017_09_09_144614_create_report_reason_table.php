<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportReasonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_reason', function (Blueprint $table) {
            $table->integer('type')->unsigned();
            $table->string('display_name');
            $table->text('description');
            $table->primary('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('report_reason', function (Blueprint $table) {
            $table->drop();
        });
    }
}
