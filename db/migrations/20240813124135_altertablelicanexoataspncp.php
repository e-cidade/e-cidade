<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Altertablelicanexoataspncp extends PostgresMigration
{
    public function up()
    {
        $sql = "
            alter table licanexoataspncp DROP CONSTRAINT licanexoataspncp_licatareg_fk;
        ";
        $this->execute($sql);
    }
}
