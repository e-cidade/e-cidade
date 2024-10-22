<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc18413 extends PostgresMigration
{
    public function up()
    {

        $sql = <<<SQL

        BEGIN;

        SELECT fc_startsession();

        UPDATE pagordem
        SET
        e50_contribuicaoprev = ''
        WHERE
        e50_contribuicaoprev like '1';

        COMMIT;

SQL;
        $this->execute($sql);
    }
}
