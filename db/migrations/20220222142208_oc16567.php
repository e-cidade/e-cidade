<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc16567 extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL

        BEGIN;

        INSERT INTO db_layouttxt
        VALUES (229, 'SICOM AM CONCIBANC', 3, 'SICOM AM - Arquivo Concilicao Bancaria', 3);

        COMMIT;
SQL;

     $this->execute($sql);
    }
}
