<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

class Oc22979 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $iIdItem = DB::table('configuracoes.db_itensmenu')
        ->where('descricao', '=', mb_convert_encoding('Utilitários da Contabilidade', 'UTF-8', 'ISO-8859-1'))
        ->select('id_item')
        ->first()->id_item;

        DB::table('configuracoes.db_menu')->where('id_item_filho', '=', function (Builder $query) {
            $query->select('id_item')
            ->from('configuracoes.db_itensmenu')
            ->where('descricao', '=', mb_convert_encoding('Importação Saldo Conta Corrente', 'UTF-8', 'ISO-8859-1'));
        })
        ->update(['id_item' => $iIdItem]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        $iIdItem = DB::table('configuracoes.db_itensmenu')
        ->where('descricao', '=', mb_convert_encoding('Abertura do Exercício', 'UTF-8', 'ISO-8859-1'))
        ->select('id_item')
        ->first()->id_item;

        DB::table('configuracoes.db_menu')->where('id_item_filho', '=', function (Builder $query) {
            $query->select('id_item')
            ->from('configuracoes.db_itensmenu')
            ->where('descricao', '=', mb_convert_encoding('Importação Saldo Conta Corrente', 'UTF-8', 'ISO-8859-1'));
        })
        ->update(['id_item' => $iIdItem]);

    }
}
