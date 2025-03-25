<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateItensmenuItemativoSicomandlicitacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            UPDATE configuracoes.db_itensmenu
            SET itemativo = 2
            WHERE funcao IN (
                'con4_gerareditais.php'
            )
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("
            UPDATE configuracoes.db_itensmenu
            SET itemativo = 1
            WHERE funcao IN (
                'con4_gerareditais.php'
            )
        ");
    }
}
