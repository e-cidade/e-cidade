<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class FixOc19034 extends PostgresMigration
{
    public function up()
    {
        $sql = "BEGIN;

        UPDATE licitaparam set l12_numeracaomanual = true;

        COMMIT;";

        $this->execute($sql);
    }
}
