<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc16474 extends PostgresMigration
{

    public function up()
    {
        $sql = "ALTER TABLE precoreferencia ADD COLUMN si01_impjustificativa bool NULL DEFAULT false;";
        $this->execute($sql);
    }
}
