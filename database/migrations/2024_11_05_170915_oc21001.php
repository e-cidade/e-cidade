<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

class Oc21001 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $descrMenuPai   = 'Relatórios de Acompanhamento';
        $descrModulo    = 'Contabilidade';
        $descrNovoMenu  = 'Despesa Corrente X Receita Corrente';
        $helperNovoMenu = 'Relatorio de acompanhamento de Receita Corrente.';
        $arquivoMenu    = 'con2_despesareceitacorrente001.php';

        $iIdItem = DB::table('configuracoes.db_itensmenu')->max('id_item') + 1;

        $iIdPai = DB::table('configuracoes.db_itensmenu')
        ->where('descricao', '=', mb_convert_encoding($descrMenuPai, 'UTF-8', 'ISO-8859-1'))
        ->first()->id_item;

        $iMenuSequencia = DB::table('configuracoes.db_itensmenu')->where('id_item', '=', $iIdPai)->max('id_item');

        $iIdModulo = DB::table('configuracoes.db_modulos')
        ->where('descr_modulo', '=', $descrModulo)
        ->first()->id_item;

        DB::table('configuracoes.db_itensmenu')->insert([
            'id_item'    => $iIdItem,
            'descricao'  => $descrNovoMenu,
            'help'       => mb_convert_encoding($helperNovoMenu, 'UTF-8', 'ISO-8859-1'),
            'funcao'     => $arquivoMenu,
            'itemativo'  => 1,
            'manutencao' => 1,
            'desctec'    => mb_convert_encoding($helperNovoMenu, 'UTF-8', 'ISO-8859-1'),
            'libcliente' => 't'
        ]);

        DB::table('configuracoes.db_menu')->insert([
            'id_item'       => $iIdPai,
            'id_item_filho' => $iIdItem,
            'menusequencia' => $iMenuSequencia,
            'modulo'        => $iIdModulo
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $descrMenu  = 'Despesa Corrente X Receita Corrente';

        DB::table('configuracoes.db_menu')->where('id_item_filho', '=', function (Builder $query) use ($descrMenu) {
            $query->select('id_item')
            ->from('configuracoes.db_itensmenu')
            ->where('descricao', '=', $descrMenu);
        })->delete();

        DB::table('configuracoes.db_itensmenu')->where('descricao', '=', $descrMenu)->delete();
    }
}
