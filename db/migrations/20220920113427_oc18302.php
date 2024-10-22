<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc18302 extends PostgresMigration
{

    public function up()
    {
        $sql = "
        select fc_startsession();

        begin;

        alter table atolegal add ed05_i_aparecerelatorio bool default false;

        commit;

        ";
        $this->execute($sql);
    }
}
