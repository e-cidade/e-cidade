<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJulgitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licitacao.julgitem', function (Blueprint $table) {
            $table->id('l30_codigo');
            $table->integer('l30_julgitemstatus');
            $table->integer('l30_orcamitem')->nullable();
            $table->integer('l30_numerolote')->nullable();
            $table->timestamp('l30_created_at')->useCurrent();
            $table->timestamp('l30_updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('l30_julgitemstatus')->references('l31_codigo')->on('licitacao.julgitemstatus');
            $table->foreign('l30_orcamitem')->references('pc22_orcamitem')->on('compras.pcorcamitem');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licitacao.julgitem');
    }
}
