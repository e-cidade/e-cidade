<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc21405 extends PostgresMigration
{

    public function up()
    {

        $sql = "
        begin;
            alter table protocolos alter column p101_observacao type varchar (600);
        commit;
        ";

        $this->execute($sql);

    }
}
