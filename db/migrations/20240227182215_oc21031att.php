<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc21031att extends PostgresMigration
{

    public function up()
    {
        $sql = "
            alter table liccontrolepncpitens add column l214_fornecedor int8;
            alter table liccontrolepncpitens add column l214_sequencialresultado int8;
        ";
        $this->execute($sql);
    }
}
