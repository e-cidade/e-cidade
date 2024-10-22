<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22365 extends PostgresMigration
{
    public function up()
    {
        $sSql = "ALTER TABLE ralic102024 ALTER COLUMN si180_vlcontratacao TYPE float8;";
        $this->execute($sSql);
    }
}
