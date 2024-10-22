<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc18367 extends PostgresMigration
{

    public function up()
    {
        $sql = "
            alter table credenciamentosaldo add column l213_valorcontratado double precision;
        ";
        $this->execute($sql);
    }
}
