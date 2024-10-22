<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc18673 extends PostgresMigration
{
    public function up()
    {
        $sql=" BEGIN;
        INSERT INTO retencaotipocalc VALUES(8,'SEST');
        INSERT INTO retencaotipocalc VALUES(9,'SENAT');
        COMMIT;
        ";
        $this->execute($sql);
    }
}
