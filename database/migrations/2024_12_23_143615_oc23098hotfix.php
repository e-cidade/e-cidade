<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

class Oc23098Hotfix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $descrMenuPai   = 'Assinatura Digital';
        $descrModulo    = 'Configuração';
        $descrNovoMenu  = 'Duplicar Assinantes';
        $helperNovoMenu = 'Duplica assinantes do ano atual para o ano seguinte.';
        $arquivoMenu    = 'con1_assinaturadigitalvirada.php';

        $iIdItem = DB::table('configuracoes.db_itensmenu')->max('id_item') + 1;

        $iIdPai = DB::table('configuracoes.db_itensmenu')
        ->where('descricao', '=', $descrMenuPai)
        ->first()->id_item;

        $iMenuSequencia = DB::table('configuracoes.db_itensmenu')->where('id_item', '=', $iIdPai)->max('id_item');

        $iIdModulo = DB::table('configuracoes.db_modulos')
        ->where('descr_modulo', '=', $descrModulo)
        ->first()->id_item;

        DB::table('configuracoes.db_itensmenu')->insert([
            'id_item'    => $iIdItem,
            'descricao'  => $descrNovoMenu,
            'help'       => $helperNovoMenu,
            'funcao'     => $arquivoMenu,
            'itemativo'  => 1,
            'manutencao' => 1,
            'desctec'    => $descrModulo,
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
        $descrMenu  = 'Duplicar Assinantes';

        DB::table('configuracoes.db_menu')->where('id_item_filho', '=', function (Builder $query) use ($descrMenu) {
            $query->select('id_item')
            ->from('configuracoes.db_itensmenu')
            ->where('descricao', '=', $descrMenu);
        })->delete();

        DB::table('configuracoes.db_itensmenu')->where('descricao', '=', $descrMenu)->delete();
    }
}
