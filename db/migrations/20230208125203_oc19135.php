<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc19135 extends PostgresMigration
{
    public function up()
    {
        $sql = "select fc_startsession();
                begin;
                alter table flpgo102023 drop column si195_dscnatcargo;
                commit;"
                ;

        $this->execute($sql);
    }
}
