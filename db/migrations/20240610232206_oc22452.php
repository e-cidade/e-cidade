<?php

use Phinx\Migration\AbstractMigration;

class Oc22452 extends AbstractMigration
{
    use App\Support\Database\InsertMenu;

    public function up()
    {
        $descrMenuPai = 'Manutenção de dados';
        $helperMenuPai = 'Manutenção de dados';
        $descrNovoMenu = 'Manutenção de Anulações de Empenhos';
        $helperNovoMenu = 'Rotina de ajuste de data e/ou exclusão de anulação de empenhos.';

        $this->criarMenuManutAnulacaoEmp($descrMenuPai, $helperMenuPai, $descrNovoMenu, $helperNovoMenu);
    }

    public function criarMenuManutAnulacaoEmp($descrMenuPai, $helperMenuPai, $descrMenu, $helperMenu)
    {
        $this->insertItemMenu($descrMenu, 'con4_manutencaoanulacaoempenhos.php', $helperMenu);

        $this->insertMenu($descrMenuPai, null, 1, $helperMenuPai);
    }
}
