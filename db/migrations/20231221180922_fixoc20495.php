<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Fixoc20495 extends PostgresMigration
{

    public function up()
    {
        $sql = "INSERT INTO solicitacaotipo VALUES (8,'Solicita��o Unificada');";
        $this->execute($sql);
    }
}
