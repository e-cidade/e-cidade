<?php

use App\Support\Database\InsertMenu;
use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22764 extends PostgresMigration
{
    use InsertMenu;

    public function up()
    {
        $descrModulo = 'Or�amento';
        $descrMenuPai = 'Metas da Despesa';
        $helperMenuPai = 'Previs�o da Despesa';
        $descrNovoMenu = 'Renumerar Dota��es';
        $helperNovoMenu = 'Renumerar as dota��es, seguindo o estrutural das dota��es por institui��o.';

        $this->criarMenuManutAnulacaoEmp($descrMenuPai, $helperMenuPai, $descrNovoMenu, $helperNovoMenu, $descrModulo);
    }

    private function criarMenuManutAnulacaoEmp($descrMenuPai, $helperMenuPai, $descrNovoMenu, $helperNovoMenu, $descrModulo)
    {
        $linkMenu = 'orc2_renumeraDotacoes_001.php';
        $this->insertItemMenu($descrNovoMenu, $linkMenu, $helperNovoMenu);

        $this->insertMenu($descrMenuPai, $descrModulo, $helperMenuPai);
    }
}
