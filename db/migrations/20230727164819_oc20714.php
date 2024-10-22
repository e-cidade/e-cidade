<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc20714 extends PostgresMigration
{
    public function up()
    {
        $sSql = "
        BEGIN;
        ALTER TABLE veiculos ALTER COLUMN ve01_codigoant TYPE VARCHAR(10);
        COMMIT;";
        $this->execute($sSql);
    }
}
