<?php

use App\Support\Database\InsertMenu;
use ECidade\Suporte\Phinx\PostgresMigration;

class Oc21943 extends PostgresMigration
{
    use InsertMenu;

    public function up()
    {
        $descrMenuPai = 'Conta Corrente';
        $helperMenuPai = 'Rotinas destinada a manuten��o de conta corrente';
        $idModuloPai = 209; //Contabilidade
        $descrNovoMenu = 'Corre��o de Fonte C/C';
        $helperNovoMenu = 'Rotina de ajuste nas fontes de Conta Corrente';

        $this->criarMenuCorrecaoFonte($descrMenuPai, $helperMenuPai, $idModuloPai, $descrNovoMenu, $helperNovoMenu);
    }

    public function criarMenuCorrecaoFonte($descrMenuPai, $helperMenuPai, $idModuloPai, $descrMenu, $helperMenu)
    {
        $this->insertItemMenu($descrMenu, '', $helperMenu);

        $this->insertMenu($descrMenuPai, null, $idModuloPai, $helperMenuPai);

        $this->criarMenuCorrecaoFonteInclusao($descrMenu, $helperMenu, $idModuloPai);
        $this->criarMenuCorrecaoFonteAlteracao($descrMenu, $helperMenu, $idModuloPai);
        $this->criarMenuCorrecaoFonteExclusao($descrMenu, $helperMenu, $idModuloPai);
    }

    public function criarMenuCorrecaoFonteInclusao($descrMenuPai, $helperMenuPai, $idModuloPai)
    {
        $descrNovoMenu = "Inclus�o";
        $linkNovoMenu = "con1_ajustafonterecurso001.php";
        $helperNovoMenu = $descrMenuPai." - Inclus�o";

        $this->insertItemMenu($descrNovoMenu, $linkNovoMenu, $helperNovoMenu);

        $this->insertMenu($descrMenuPai, null, $idModuloPai, $helperMenuPai);
    }

    public function criarMenuCorrecaoFonteAlteracao($descrMenuPai, $helperMenuPai, $idModuloPai)
    {
        $descrNovoMenu = "Altera��o";
        $linkNovoMenu = "con1_ajustafonterecurso002.php";
        $helperNovoMenu = $descrMenuPai." - Altera��o";

        $this->insertItemMenu($descrNovoMenu, $linkNovoMenu, $helperNovoMenu);

        $this->insertMenu($descrMenuPai, null, $idModuloPai, $helperMenuPai);
    }

    public function criarMenuCorrecaoFonteExclusao($descrMenuPai, $helperMenuPai, $idModuloPai)
    {
        $descrNovoMenu = "Exclus�o";
        $linkNovoMenu = "con1_ajustafonterecurso003.php";
        $helperNovoMenu = $descrMenuPai." - Exclus�o";

        $this->insertItemMenu($descrNovoMenu, $linkNovoMenu, $helperNovoMenu);

        $this->insertMenu($descrMenuPai, null, $idModuloPai, $helperMenuPai);
    }
}
