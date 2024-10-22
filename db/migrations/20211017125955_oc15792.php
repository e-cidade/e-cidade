<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc15792 extends PostgresMigration
{
    public function up()
    {
        $this->execute("ALTER TABLE precoreferencia ADD column si01_cotacaoitem int");

    }
}
