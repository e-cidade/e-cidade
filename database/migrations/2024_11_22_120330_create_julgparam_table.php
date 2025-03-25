<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJulgparamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licitacao.julgparam', function (Blueprint $table) {
            $table->id('l13_julgparam');
            $table->string('l13_instit');
            $table->boolean('l13_precoref');
            $table->decimal('l13_difminlance')->nullable();
            $table->integer('l13_clapercent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licitacao.julgparam');
    }
}