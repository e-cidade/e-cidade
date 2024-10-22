<?php

use App\Support\Database\InsertMenu;
use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22764 extends PostgresMigration
{
    use InsertMenu;

    public function up()
    {
        $descrModulo = 'Orçamento';
        $descrMenuPai = 'Metas da Despesa';
        $helperMenuPai = 'Previsão da Despesa';
        $descrNovoMenu = 'Renumerar Dotações';
        $helperNovoMenu = 'Renumerar as dotações, seguindo o estrutural das dotações por instituição.';

        $this->criarMenuManutAnulacaoEmp($descrMenuPai, $helperMenuPai, $descrNovoMenu, $helperNovoMenu, $descrModulo);
    }

    private function criarMenuManutAnulacaoEmp($descrMenuPai, $helperMenuPai, $descrNovoMenu, $helperNovoMenu, $descrModulo)
    {
        $linkMenu = 'orc2_renumeraDotacoes_001.php';
        $this->insertItemMenu($descrNovoMenu, $linkMenu, $helperNovoMenu);

        $this->insertMenu($descrMenuPai, $descrModulo, $helperMenuPai);
    }
}
