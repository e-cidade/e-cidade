<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDbmenuMigracaocompras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "
                update configuracoes.db_itensmenu set funcao = 'com1_subgrupos.php' where funcao='mig1_subgrupos.php';
                update configuracoes.db_itensmenu set funcao = 'com1_pcmater.php' where funcao='mig1_pcmater.php';
                update configuracoes.db_itensmenu set funcao = 'com1_processossemrp.php' where funcao='mig1_processossemrp.php';
        ";
        DB::unprepared($sql);
    }
}
