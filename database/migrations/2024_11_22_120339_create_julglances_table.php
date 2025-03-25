<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJulglancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licitacao.julglances', function (Blueprint $table) {
            $table->id('l32_codigo');
            $table->integer('l32_julgitem');
            $table->integer('l32_julgforne');
            $table->double('l32_lance')->nullable();
            $table->timestamp('l32_created_at')->useCurrent();
            $table->timestamp('l32_updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('l32_julgitem')->references('l30_codigo')->on('licitacao.julgitem');
            $table->foreign('l32_julgforne')->references('l34_codigo')->on('licitacao.julgforne');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licitacao.julglances');
    }
}
