<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Addcamposigiloso extends PostgresMigration
{
    public function up()
    {
        $sql = "alter table liclicitem add column l21_sigilo bool";
        $this->execute($sql);
    }
}
