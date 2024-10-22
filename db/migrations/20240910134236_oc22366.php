<?php

use Phinx\Migration\AbstractMigration;

class Oc22366 extends AbstractMigration
{
    public function up()
    {
        $sql = "
            INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Materiais', 'Materiais', 'com1_pcmater.php', 1, 1, 'Migraчуo Materiais', 't');
            INSERT INTO db_menu VALUES((select max(id_item) from db_itensmenu where descricao='Migraчуo'),(select max(id_item) from db_itensmenu),2,28);
        ";
    }
}
