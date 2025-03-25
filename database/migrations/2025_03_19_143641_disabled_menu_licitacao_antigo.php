<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DisabledMenuLicitacaoAntigo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            UPDATE configuracoes.db_itensmenu SET 
                libcliente = false
            WHERE
                descricao ILIKE 'Licita%o' AND (funcao IS NULL OR funcao = '')
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
