<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class OcSeqDdc extends PostgresMigration
{
    public function up()
    {
        $sql = "
            DROP SEQUENCE IF EXISTS ddc102022_si150_sequencial_seq;
            CREATE SEQUENCE ddc102022_si150_sequencial_seq;
        ";
        $this->execute($sql);
    }
}
