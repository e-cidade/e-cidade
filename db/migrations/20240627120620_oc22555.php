<?php

use Phinx\Migration\AbstractMigration;

class Oc22555 extends AbstractMigration
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