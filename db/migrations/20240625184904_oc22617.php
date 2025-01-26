<?php

use App\Support\Database\InsertMenu;
use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22617 extends PostgresMigration
{
    use InsertMenu;

    public function up()
    {
        $idMenuPai   = 4152;
        $helperMenuPai  = '';
        $idModuloPai    = 209;
        $titleNovoMenu  = 'Menu servir� para realizar a gera��o de relat�rios de conv�nios';
        $descrNovoMenu  = 'Conv�nios';

        $this->criarMenuConvenios($idMenuPai, $helperMenuPai, $idModuloPai, $descrNovoMenu, $titleNovoMenu);
    }

    public function criarMenuConvenios($idMenuPai, $helperMenuPai, $idModuloPai, $descrNovoMenu, $titleNovoMenu)
    {
        $linkNovoMenu   = "con2_convenios_001.php";

        $this->insertItemMenu($descrNovoMenu, $linkNovoMenu, $titleNovoMenu);

        $this->insertMenuById($idMenuPai, null, $idModuloPai);
    }
}
