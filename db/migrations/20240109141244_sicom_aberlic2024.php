<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class SicomAberlic2024 extends PostgresMigration
{

    public function up()
    {
        $sql = "
            ALTER TABLE aberlic102024 add column si46_codunidadesubedital int8;
        ";

        $this->execute($sql);
    }
}
