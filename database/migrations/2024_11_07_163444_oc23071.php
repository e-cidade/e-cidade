<?php

use App\Support\Database\InsertMenu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Oc23071 extends Migration
{
    use InsertMenu;

    private const NOVO_MENU_DESC = 'Reprocessar Saldos Balancete';
    private const DESCR_MENU_PAI = 'Utilitários da Contabilidade';
    private const NOME_MODULO = 'Contabilidade';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $linkMenu = 'con4_reprocessasaldosbalancete001.php';

        $this->insertItemMenu(self::NOVO_MENU_DESC, $linkMenu, self::NOVO_MENU_DESC);
        $this->insertMenu(self::DESCR_MENU_PAI, self::NOME_MODULO);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $subQuery = DB::table('configuracoes.db_itensmenu')
            ->select('id_item')
            ->where('descricao', self::NOVO_MENU_DESC)
            ->where('help', self::NOVO_MENU_DESC);

        DB::table('configuracoes.db_menu')
            ->whereIn('id_item_filho', $subQuery)
            ->delete();

        DB::table('configuracoes.db_itensmenu')
            ->where('descricao', self::NOVO_MENU_DESC)
            ->where('help', self::NOVO_MENU_DESC)
            ->delete();
    }

}
