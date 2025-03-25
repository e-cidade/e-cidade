<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJulgitemhistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licitacao.julgitemhist', function (Blueprint $table) {
            $table->id('l33_codigo');
            $table->integer('l33_julgitem');
            $table->integer('l33_julgitemstatus');
            $table->text('l33_motivo');
            $table->timestamp('l33_created_at')->useCurrent();
            $table->timestamp('l33_updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('l33_julgitem')->references('l30_codigo')->on('licitacao.julgitem');
            $table->foreign('l33_julgitemstatus')->references('l31_codigo')->on('licitacao.julgitemstatus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licitacao.julgitemhist');
    }
}
