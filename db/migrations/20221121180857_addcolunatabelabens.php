<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Addcolunatabelabens extends PostgresMigration
{
    public function up()
    {

        $sql = <<<SQL

        BEGIN;

        SELECT fc_startsession();

        ALTER TABLE patrimonio.bens ADD t52_codexterno bigint;

        COMMIT;

SQL;
        $this->execute($sql);
    }
}
