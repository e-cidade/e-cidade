<?php

use App\Support\Database\InsertMenu;
use Phinx\Migration\AbstractMigration;

class Oc22617 extends AbstractMigration
{
    use InsertMenu;

    public function up()
    {
        $idMenuPai   = 4152;
        $helperMenuPai  = '';
        $idModuloPai    = 209;
        $titleNovoMenu  = 'Menu servirá para realizar a geração de relatórios de convênios';
        $descrNovoMenu  = 'Convênios';

        $this->criarMenuConvenios($idMenuPai, $helperMenuPai, $idModuloPai, $descrNovoMenu, $titleNovoMenu);
    }

    public function criarMenuConvenios($idMenuPai, $helperMenuPai, $idModuloPai, $descrNovoMenu, $titleNovoMenu)
    {
        $linkNovoMenu   = "con2_convenios_001.php";

        $this->insertItemMenu($descrNovoMenu, $linkNovoMenu, $titleNovoMenu);

        $this->insertMenuById($idMenuPai, null, $idModuloPai);
    }
}
 