<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc18574migraton extends PostgresMigration
{

    public function up()
    {
        $sql = "BEGIN;
        UPDATE db_itensmenu SET descricao='Rol de Ades�o a Ata de Registro de Pre�o',help='Rol de Ades�o a Ata de Registro de Pre�o',desctec='Rol de Ades�o a Ata de Registro de Pre�o'  WHERE funcao='com2_relatorioroldeadesao.php';
        COMMIT;";

        $this->execute($sql);
    }
}
