<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flat', function (Blueprint $table) {
            $table->increments('id');
            $table->string('block');
            $table->string('floor');
            $table->integer('name');
            $table->float('price');
            $table->float('area');
            $table->string('view');
            $table->integer('residence_id')->unsigned();
        });

        Schema::table('flat', function (Blueprint $table) {
            $table->foreign('residence_id')->references('id')->on('residence');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('flat');
    }
}
