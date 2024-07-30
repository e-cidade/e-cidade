<?php

use Phinx\Migration\AbstractMigration;
use App\Support\Database\InsertMenu;

class Oc22557 extends AbstractMigration
{
    use InsertMenu;

    public function up()
    {
        $descrMenuPai = 'Metas da Despesa';
        $idModuloPai = 116; //Orçamento
        $helperItemPai = 'Previsão da Despesa';
        $descrNovoMenu = 'Edição em Bloco';
        $helperNovoMenu = 'Edição em bloco de metas da despesa';
        $arquivoMenu = 'orc4_manutdotacaobloco001.php';

        $this->criaMenuNovo($descrMenuPai, $idModuloPai, $descrNovoMenu, $arquivoMenu, $helperNovoMenu, $helperItemPai);
    }

    public function criaMenuNovo($descrMenuPai, $idModuloPai, $descrNovoMenu, $arquivoMenu, $helperNovoMenu, $helperItemPai)
    {
        $this->insertItemMenu($descrNovoMenu, $arquivoMenu, $helperNovoMenu);

        $this->insertMenu($descrMenuPai, null, $idModuloPai, $helperItemPai);
    }
}
