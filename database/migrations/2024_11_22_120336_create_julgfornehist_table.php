<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJulgfornehistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licitacao.julgfornehist', function (Blueprint $table) {
            $table->id('l36_codigo');
            $table->integer('l36_julgforne');
            $table->integer('l36_julgfornestatus');
            $table->text('l36_motivo');
            $table->timestamp('l36_created_at')->useCurrent();
            $table->timestamp('l36_updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('l36_julgforne')->references('l34_codigo')->on('licitacao.julgforne');
            $table->foreign('l36_julgfornestatus')->references('l35_codigo')->on('licitacao.julgfornestatus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licitacao.julgfornehist');
    }
}
