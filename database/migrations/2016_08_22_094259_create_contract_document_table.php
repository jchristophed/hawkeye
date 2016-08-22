<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_document', function (Blueprint $table) {
            $table->integer('contract_id')->unsigned();
            $table->integer('document_id')->unsigned();
            $table->primary(['contract_id', 'document_id']);
        });

        Schema::table('contract_document', function (Blueprint $table) {
            $table->foreign('contract_id')->references('id')->on('contract')->onDelete('cascade');;
            $table->foreign('document_id')->references('id')->on('document')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contract_document');
    }
}
