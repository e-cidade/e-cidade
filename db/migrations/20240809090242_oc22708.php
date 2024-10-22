<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22708 extends PostgresMigration
{
    public function up()
    {

        $sql = <<<SQL

        BEGIN;

        SELECT fc_startsession();

        ALTER TABLE empempenho ADD COLUMN e60_dividaconsolidada INT8;

        COMMIT;

SQL;
        $this->execute($sql);
    }
}
