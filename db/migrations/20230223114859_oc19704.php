<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc19704 extends PostgresMigration
{

    public function up()
    {
        $sql = "BEGIN;
        ALTER TABLE empparametro ADD e30_modeloautempenho INT;
        UPDATE empparametro SET e30_modeloautempenho = 5;
        COMMIT;";

        $this->execute($sql);

    }
}
