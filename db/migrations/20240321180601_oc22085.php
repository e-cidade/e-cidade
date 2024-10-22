<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22085 extends PostgresMigration
{

    public function up()
    {
        $sql = "
            alter table acocontroletermospncp add column l214_tipotermocontratoid int4;
        ";
        $this->execute($sql);
    }
}
