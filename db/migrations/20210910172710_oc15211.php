<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc15211 extends PostgresMigration
{
    public function up()
    {
        $sql = "ALTER TABLE veicmanut ADD column ve62_itensempenho int;";
        $this->execute($sql);
    }
}
