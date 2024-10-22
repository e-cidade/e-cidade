<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc17168 extends PostgresMigration
{
    public function up()
    {
        $sSql = "
        ALTER TABLE empempenho ADD COLUMN e60_vlrutilizado float;
        ALTER TABLE veicparam ADD COLUMN ve50_abastempenho int;
        ALTER TABLE veicparam ADD COLUMN ve50_datacorte date;
        ";
        $this->execute($sSql);
    }
}
