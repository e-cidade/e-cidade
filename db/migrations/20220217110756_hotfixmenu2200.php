<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Hotfixmenu2200 extends PostgresMigration
{
    public function up()
    {
        $sql = "
        BEGIN;
        UPDATE configuracoes.db_itensmenu SET funcao='con4_manutencaoformulario001.php?esocial=25' WHERE descricao ilike 'S-2200%';
        COMMIT;
        ";
        $this->execute($sql);
    }
}
