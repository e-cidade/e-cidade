<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc15722 extends PostgresMigration
{

    public function up()
    {
        $this->execute("ALTER TABLE manutencaolicitacao ALTER COLUMN manutlic_codunidsubanterior TYPE varchar(8)");
        $this->execute("ALTER TABLE manutencaoacordo ALTER COLUMN manutac_codunidsubanterior TYPE varchar(8)");

    }
}
