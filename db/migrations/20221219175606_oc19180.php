<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc19180 extends PostgresMigration
{

    public function up()
    {
        $sql = "BEGIN;

        INSERT INTO db_viradacaditem (c33_sequencial,c33_descricao) VALUES (35,'AJUSTES DE NUMERA��O - PATRIMONIAL');

        COMMIT;
        ";

        $this->execute($sql);
    }
}
