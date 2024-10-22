<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Addcampopcmater extends PostgresMigration
{

    public function up()
    {
        $sql = "
            ALTER TABLE pcmater ADD COLUMN pc01_codmaterant int4;
        ";
        $this->execute($sql);
    }
}
