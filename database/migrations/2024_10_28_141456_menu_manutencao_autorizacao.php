<?php

use Illuminate\Database\Migrations\Migration;

class MenuManutencaoAutorizacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sNomeMenu = utf8_encode('Autorizações de Empenho');
        $sql = "
                INSERT INTO configuracoes.db_itensmenu values ((select max(id_item)+1 from configuracoes.db_itensmenu),
                '$sNomeMenu','$sNomeMenu','m4_datasautorizacaoempenho.php',1,1,'$sNomeMenu','t');

                INSERT INTO configuracoes.db_menu VALUES((select id_item from configuracoes.db_itensmenu where descricao = 'Controle de Datas'),(select max(id_item) from configuracoes.db_itensmenu),5,1);
            ";
        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = "
            DELETE FROM configuracoes.db_menu WHERE id_item_filho = (SELECT id_item FROM configuracoes.db_itensmenu WHERE descricao = 'Autorizações de Empenho');
            DELETE FROM configuracoes.db_itensmenu WHERE descricao = 'Autorizações de Empenho';
        ";
        DB::unprepared($sql);
    }
}
