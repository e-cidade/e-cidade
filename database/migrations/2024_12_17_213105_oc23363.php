<?php

use Illuminate\Database\Migrations\Migration;
use App\Support\Database\InsertMenu;

class Oc23363 extends Migration
{
    use InsertMenu;

    public function up()
    {
        
        $descrMenuPai   = 'Relatórios de Acompanhamento';
        $descrModulo    = 'Contabilidade'; // tabela db_modulos
        $helpItemPai    = 'Relatórios de Acompanhamento';
        $desctecItemPai = 'Relatórios de Acompanhamento';

        $descrNovoMenu  = 'Gastos com Folha CÂMARA';
        $arquivoMenu    = 'con2_gastoscomfolhacamara_001.php';
        $helpNovoMenu   = 'Gastos com Folha CÂMARA';
        $this->criaMenuNovo($descrMenuPai, $descrModulo, $descrNovoMenu, $arquivoMenu, $helpNovoMenu, $helpItemPai, $desctecItemPai);
    }

    // DB:FINANCEIRO > Contabilidade > Relatórios > Relatórios de Acompanhamento > Gastos com Folha CÂMARA

    private function criaMenuNovo($descrMenuPai, $descrModulo, $descrNovoMenu, $arquivoMenu, $helpNovoMenu, $helpItemPai, $desctecItemPai)
    {
        $this->insertItemMenu($descrNovoMenu, $arquivoMenu, $helpNovoMenu);

        $this->insertMenu($descrMenuPai, $descrModulo, $helpItemPai, $desctecItemPai);
    }
}
