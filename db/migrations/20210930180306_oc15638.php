<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc15638 extends PostgresMigration
{

    public function up()
    {
        $this->execute("ALTER TABLE veicmanutitem ALTER COLUMN ve63_descr TYPE VARCHAR(50)");

    }
}
