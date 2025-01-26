<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc15153 extends PostgresMigration
{
    public function up()
    {
        $sql = "UPDATE orctiporec SET o15_descr = REPLACE(o15_descr,'INDIVIDUAIS -','-') WHERE o15_codigo IN (164,264);";
        $this->execute($sql);
    }
}
