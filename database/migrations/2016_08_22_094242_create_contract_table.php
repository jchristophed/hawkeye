<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract', function (Blueprint $table) {
            $table->increments('id');
            $table->date('start_date');
            $table->date('end_date');
            $table->float('price');
            $table->float('application_fee');
            $table->float('deposit');
            $table->string('mode_of_payment');
            $table->string('status');
            $table->boolean('folder');
            $table->integer('flat_id')->unsigned();
            $table->integer('tenant_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('contract', function (Blueprint $table) {
            $table->foreign('flat_id')->references('id')->on('flat');
            $table->foreign('tenant_id')->references('id')->on('tenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contract');
    }
}
