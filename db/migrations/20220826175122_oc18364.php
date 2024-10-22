<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc18364 extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL
            begin;

                alter table matmater add column m60_instit int4;
            commit;
SQL;
        $this->execute($sql);
    }
}
