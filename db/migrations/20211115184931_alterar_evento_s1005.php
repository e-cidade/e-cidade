<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class AlterarEventoS1005 extends PostgresMigration
{

    public function up()
    {
        $sql = "
        UPDATE avaliacao SET db101_identificador = 's1005-vs1' WHERE db101_identificador = 'S1005';
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
        UPDATE avaliacao SET db101_identificador = 'S1005' WHERE db101_identificador = 's1005-vs1';
        ";

        $this->execute($sql);
    }
}
