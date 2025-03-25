<?php

use App\Support\Database\InsertMenu;
use Illuminate\Database\Migrations\Migration;

class Oc23097 extends Migration
{
    use InsertMenu;

    public function up()
    {
        $descrMenuPai   = 'Relatórios';
        $descrModulo    = 'Configuração';
        $helpItemPai    = 'relatórios';
        $desctecItemPai = '';

        $descrNovoMenu  = 'Assinantes';
        $arquivoMenu    = 'con2_assinantes_001.php';
        $helpNovoMenu   = 'Relatório de Assinantes.';
        $this->criaMenuNovo($descrMenuPai, $descrModulo, $descrNovoMenu, $arquivoMenu, $helpNovoMenu, $helpItemPai, $desctecItemPai);
    }

    //DB:CONFIGURAÇÃO > Configuração > Relatórios > Assinantes

    private function criaMenuNovo($descrMenuPai, $descrModulo, $descrNovoMenu, $arquivoMenu, $helpNovoMenu, $helpItemPai, $desctecItemPai)
    {
        $this->insertItemMenu($descrNovoMenu, $arquivoMenu, $helpNovoMenu);

        $this->insertMenu($descrMenuPai, $descrModulo, $helpItemPai, $desctecItemPai);
    }
}