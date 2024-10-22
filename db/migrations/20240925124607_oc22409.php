<?php

use Phinx\Migration\AbstractMigration;

class Oc22409 extends AbstractMigration
{
    public function up()
    {
        $sql = "
            INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Processos sem RP', 'Processos sem RP', '', 1, 1, 'Processos sem RP', 't');
            INSERT INTO db_menu VALUES((select max(id_item) from db_itensmenu where descricao='Migração'),(select max(id_item) from db_itensmenu),3,28);

            INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Processo de Compras', 'Processo de Compras', 'com1_processossemrp.php', 1, 1, 'Processo de Compras', 't');
            INSERT INTO db_menu VALUES((select max(id_item) from db_itensmenu where descricao='Processos sem RP'),(select max(id_item) from db_itensmenu),1,28);
        ";

        $this->execute($sql);
    }
}
