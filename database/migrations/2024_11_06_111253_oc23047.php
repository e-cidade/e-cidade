<?php

use App\Support\Database\InsertMenu;
use Illuminate\Database\Migrations\Migration;

class Oc23047 extends Migration
{
    use InsertMenu;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $descrMenuPai = 'Manutenção de dados';
        $descrModulo = 'Configuração';
        $descrNovoMenu = 'Alterar forma de controle empenho';
        $helperNovoMenu = 'Rotina de ajuste para alterar a forma de controle de empenhos.';

        $this->criarMenu($descrMenuPai, $descrModulo, $descrNovoMenu, $helperNovoMenu);
    }

    public function criarMenu($descrMenuPai, $descrModulo, $descrNovoMenu, $helperNovoMenu)
    {
        $this->insertItemMenu($descrNovoMenu, 'con4_manutencaocontroleempenhos.php', $helperNovoMenu);
        $this->insertMenu($descrMenuPai, $descrModulo, $descrMenuPai, $descrMenuPai);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
