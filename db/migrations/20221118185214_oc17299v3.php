<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc17299v3 extends PostgresMigration
{

    public function up()
    {
        $sql = "UPDATE protprocessodocumento SET p01_nivelacesso = 1;";
        $this->execute($sql);
    }
}
