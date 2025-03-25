<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJulgforneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licitacao.julgforne', function (Blueprint $table) {
            $table->id('l34_codigo');
            $table->integer('l34_julgfornestatus');
            $table->integer('l34_orcamforne');
            $table->integer('l34_orcamitem')->nullable();
            $table->integer('l34_numerolote')->nullable();
            $table->timestamp('l34_created_at')->useCurrent();
            $table->timestamp('l34_updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('l34_julgfornestatus')->references('l35_codigo')->on('licitacao.julgfornestatus');
            $table->foreign('l34_orcamforne')->references('pc21_orcamforne')->on('compras.pcorcamforne');
            $table->foreign('l34_orcamitem')->references('pc22_orcamitem')->on('compras.pcorcamitem');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licitacao.julgforne');
    }
}
