<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22452 extends PostgresMigration
{
    use App\Support\Database\InsertMenu;

    public function up()
    {
        $descrMenuPai = 'Manuten��o de dados';
        $helperMenuPai = 'Manuten��o de dados';
        $descrNovoMenu = 'Manuten��o de Anula��es de Empenhos';
        $helperNovoMenu = 'Rotina de ajuste de data e/ou exclus�o de anula��o de empenhos.';

        $this->criarMenuManutAnulacaoEmp($descrMenuPai, $helperMenuPai, $descrNovoMenu, $helperNovoMenu);
    }

    public function criarMenuManutAnulacaoEmp($descrMenuPai, $helperMenuPai, $descrMenu, $helperMenu)
    {
        $this->insertItemMenu($descrMenu, 'con4_manutencaoanulacaoempenhos.php', $helperMenu);

        $this->insertMenu($descrMenuPai, null, 1, $helperMenuPai);
    }
}
