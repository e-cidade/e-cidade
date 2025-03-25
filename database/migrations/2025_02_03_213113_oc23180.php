<?php

use App\Support\Database\InsertMenu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Oc23180 extends Migration
{
    use InsertMenu;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $descrMenuPai   = 'Dívida Consolidada';
        $descrModulo    = 'Contabilidade';
        $descrNovoMenu  = 'Previsão de Receitas';
        $helperNovoMenu = 'Previsão de Receitas';
        $arquivoMenu    = 'sic1_previsaoreceitas001.php';

        $iIdItem = DB::table('configuracoes.db_itensmenu')->max('id_item') + 1;
        
        $iIdPai = (DB::table('configuracoes.db_itensmenu')
        ->where('descricao', '=', $descrMenuPai)
        ->where('funcao', '=', '')
        ->first())->id_item;

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

        Schema::create('prevoperacaocredito', function (Blueprint $table) {
            $table->bigInteger('c242_operacaocredito')->default(0);
            $table->integer('c242_fonte')->default(0);
            $table->double('c242_vlprevisto')->default(0);
            $table->integer('c242_anousu')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}