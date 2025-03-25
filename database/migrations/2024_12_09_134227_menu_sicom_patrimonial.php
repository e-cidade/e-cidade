<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MenuSicomPatrimonial extends Migration
{


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::unprepared("
            INSERT INTO configuracoes.db_itensmenu 
            VALUES (
                (SELECT MAX(id_item) + 1 FROM configuracoes.db_itensmenu),
                'Gerar SICOM', 'Gerar SICOM', '', 1, 1, 'Gerar SICOM', 't'
            )
        ");

        DB::unprepared("
            INSERT INTO configuracoes.db_menu 
            VALUES (
                1818,
                (SELECT MAX(id_item) FROM configuracoes.db_itensmenu),
                (SELECT MAX(menusequencia) + 1 FROM db_menu WHERE id_item = 1818 AND modulo = 381),
                381
            )
        ");

        DB::unprepared("
            INSERT INTO configuracoes.db_itensmenu 
            VALUES (
                (SELECT MAX(id_item) + 1 FROM configuracoes.db_itensmenu),
                'Geraчуo de Arquivos', 'Geraчуo de Arquivos', 'lic1_gerararquivossicom.php', 1, 1, 'Geraчуo de Arquivos', 't'
            )
        ");

        DB::unprepared("
            INSERT INTO configuracoes.db_menu 
            VALUES (
                (SELECT MAX(id_item) FROM configuracoes.db_itensmenu WHERE descricao = 'Gerar SICOM'),
                (select max(id_item) from db_itensmenu),
                1,
                381
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

        // Excluir registros da tabela db_menu relacionados aos itens Gerar SICOM e Geraчуo de Arquivos
        DB::statement("
            DELETE FROM configuracoes.db_menu
            WHERE id_item_filho IN (
                (SELECT MAX(id_item) FROM db_itensmenu WHERE descricao = 'Gerar SICOM'),
                (SELECT MAX(id_item) FROM db_itensmenu WHERE descricao = 'Geraчуo de Arquivos')
            )
        ");

        // Excluir os registros da tabela db_itensmenu para os itens Gerar SICOM e Geraчуo de Arquivos
        DB::statement("
            DELETE FROM configuracoes.db_itensmenu
            WHERE id_item IN (
                (SELECT MAX(id_item) FROM configuracoes.db_itensmenu WHERE descricao = 'Gerar SICOM'),
                (SELECT MAX(id_item) FROM configuracoes.db_itensmenu WHERE descricao = 'Geraчуo de Arquivos')
            )
        ");
    }
}
