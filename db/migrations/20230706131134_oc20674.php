<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc20674 extends PostgresMigration
{

    public function up()
    {
        $sql = "ALTER table adesaoregprecos alter COLUMN si06_publicacaoaviso DROP NOT NULL;";
        $this->execute($sql);
    }
}
