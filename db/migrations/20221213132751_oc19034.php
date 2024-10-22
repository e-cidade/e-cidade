<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc19034 extends PostgresMigration
{

    public function up()
    {
        $sSql = "
        BEGIN;
        ALTER TABLE licitaparam ADD l12_numeracaomanual bool default true;
        COMMIT";
        $this->execute($sSql);
    }
}
