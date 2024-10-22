<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc21128 extends PostgresMigration
{

    public function up()
    {
        $sql = "BEGIN;

        ALTER TABLE precoreferencia ADD si01_casasdecimais INT;

        UPDATE precoreferencia SET si01_casasdecimais = 2;

        COMMIT;
        ";
        $this->execute($sql);

    }
}
