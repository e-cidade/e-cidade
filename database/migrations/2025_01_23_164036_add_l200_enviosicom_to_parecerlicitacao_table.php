<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddL200EnviosicomToParecerlicitacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('licitacao.parecerlicitacao', function (Blueprint $table) {
            $table->boolean('l200_enviosicom')->default(false); // Ajuste 'ultimo_campo_existente' conforme necessário
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('licitacao.parecerlicitacao', function (Blueprint $table) {
            $table->dropColumn('l200_enviosicom');
        });
    }
}
