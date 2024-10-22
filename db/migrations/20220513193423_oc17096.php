<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc17096 extends PostgresMigration
{

    public function up()
    {
        $sql = "
            alter table protparam add column p90_novatelaprotocolo bool null default false;";
        $this->execute($sql);
    }
}
