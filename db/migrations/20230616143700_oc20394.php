<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc20394 extends PostgresMigration
{

    public function up()
    {
        $sSql = "
        BEGIN;
        UPDATE db_syscampo SET nulo = 'false' WHERE nomecam = 'pc60_dtreg';
        COMMIT;
        ";

        $this->execute($sSql);
    }
}
