<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22555 extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL

        BEGIN;
        SELECT fc_startsession();

        ALTER TABLE caixa.saltes ADD k13_dtreativacaoconta date NULL;

        COMMIT;

SQL;
        $this->execute($sql);
    }
}
