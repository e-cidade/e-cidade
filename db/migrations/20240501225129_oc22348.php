<?php

use App\Support\Database\InsertMenu;
use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22348 extends PostgresMigration
{
    use InsertMenu;

    public function up()
    {

        $descrMenuPai   = 'Contas';
        $helperMenuPai  = 'Manutenção de contas';
        $idModuloPai    =  39;
        $descrNovoMenu  = 'Contas Bancárias (novo)';
        $helperNovoMenu = 'Menu servirá para realizar o cadastro completo de uma conta bancária';

        $this->novosCampos();
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
        $descrNovoMenu  = "Inclusão";
        $linkNovoMenu   = "con1_cadcontabancaria001.php";
        $helperNovoMenu = $descrMenuPai." - Inclusão";

        $this->insertItemMenu($descrNovoMenu, $linkNovoMenu, $helperNovoMenu);

        $this->insertMenu($descrMenuPai, null, $idModuloPai, $helperMenuPai);
    }

    public function criarMenuCorrecaoFonteAlteracao($descrMenuPai, $helperMenuPai, $idModuloPai)
    {
        $descrNovoMenu  = "Alteração";
        $linkNovoMenu   = "con1_cadcontabancaria002.php";
        $helperNovoMenu = $descrMenuPai." - Alteração";

        $this->insertItemMenu($descrNovoMenu, $linkNovoMenu, $helperNovoMenu);

        $this->insertMenu($descrMenuPai, null, $idModuloPai, $helperMenuPai);
    }

    public function criarMenuCorrecaoFonteExclusao($descrMenuPai, $helperMenuPai, $idModuloPai)
    {
        $descrNovoMenu  = "Exclusão";
        $linkNovoMenu   = "con1_cadcontabancaria003.php";
        $helperNovoMenu = $descrMenuPai." - Exclusão";

        $this->insertItemMenu($descrNovoMenu, $linkNovoMenu, $helperNovoMenu);

        $this->insertMenu($descrMenuPai, null, $idModuloPai, $helperMenuPai);
    }

    public function novosCampos()
    {
        $sql = <<<SQL

        BEGIN;
        SELECT fc_startsession();

        ALTER TABLE caixa.caiparametro ADD k29_estrutcontacorrente varchar(15) NULL;
        ALTER TABLE caixa.caiparametro ADD k29_estrutcontaaplicacao varchar(15) NULL;

        COMMIT;

SQL;
        $this->execute($sql);
    }
}
