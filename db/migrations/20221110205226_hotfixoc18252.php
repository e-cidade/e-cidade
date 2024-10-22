<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Hotfixoc18252 extends PostgresMigration
{

    public function change()
    {
        $sql= "BEGIN;
        Alter table parametroscontratos Add pc01_liberarsaldoposicao boolean;
        COMMIT;
        BEGIN;
        UPDATE parametroscontratos SET pc01_liberarsaldoposicao = 'f';
        COMMIT;
        ";

        $this->execute($sql);
    }
}
