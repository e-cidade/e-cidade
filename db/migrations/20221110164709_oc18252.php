<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc18252 extends PostgresMigration
{

    public function up()
    {
        $sql= "BEGIN;
        Alter table parametroscontratos Add pc01_liberarsaldoposicao boolean;
        COMMIT;
        BEGIN;
        UPDATE parametroscontratos SET pc01_liberarsaldoposicao = 'f';
        COMMIT;
        ";
    }
}
