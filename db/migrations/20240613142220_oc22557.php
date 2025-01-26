<?php

use ECidade\Suporte\Phinx\PostgresMigration;
use App\Support\Database\InsertMenu;

class Oc22557 extends PostgresMigration
{
    use InsertMenu;

    public function up()
    {
        $descrMenuPai = 'Metas da Despesa';
        $idModuloPai = 116; //Or�amento
        $helperItemPai = 'Previs�o da Despesa';
        $descrNovoMenu = 'Edi��o em Bloco';
        $helperNovoMenu = 'Edi��o em bloco de metas da despesa';
        $arquivoMenu = 'orc4_manutdotacaobloco001.php';

        $this->criaMenuNovo($descrMenuPai, $idModuloPai, $descrNovoMenu, $arquivoMenu, $helperNovoMenu, $helperItemPai);
    }

    public function criaMenuNovo($descrMenuPai, $idModuloPai, $descrNovoMenu, $arquivoMenu, $helperNovoMenu, $helperItemPai)
    {
        $this->insertItemMenu($descrNovoMenu, $arquivoMenu, $helperNovoMenu);

        $this->insertMenu($descrMenuPai, null, $idModuloPai, $helperItemPai);
    }
}
