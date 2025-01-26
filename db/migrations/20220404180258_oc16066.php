<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc16066 extends PostgresMigration
{

    public function up()
    {
        $sql = "
            update db_itensmenu set descricao = 'Registro de pre�o' where funcao = 'com4_consabertregistro001.php';
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            update db_itensmenu set descricao = 'Abertura de Registro de pre�o' where funcao = 'com4_consabertregistro001.php';
        ";
        $this->execute($sql);
    }
}
