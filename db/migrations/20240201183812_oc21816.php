<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc21816 extends PostgresMigration
{
    public function up()
    {
        $sql = "
            CREATE SEQUENCE licobras302024_si203_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
        ";
        $this->execute($sql);
    }
}
