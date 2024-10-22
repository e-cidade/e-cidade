<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc20631 extends PostgresMigration
{
    public function up()
    {
        $sql = "update acordoposicaotipo set ac27_descricao='Acréscimo/Decréscimo de item(ns) conjugado' where ac27_sequencial=14";
        $this->execute($sql);
    }
}
