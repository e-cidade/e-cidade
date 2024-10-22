<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Addcamporeceita extends PostgresMigration
{

    public function up()
    {
        $sql = "alter table liclicita add column l20_receita bool;";
        $this->execute($sql);
    }
}
