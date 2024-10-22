<?php

use Phinx\Migration\AbstractMigration;

class Oc22470 extends AbstractMigration
{
    public function up()
    {
        $sql  = "
            INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Item ME/EPP', 'Item ME/EPP', 'com01_itensmeepp.php', 1, 1, 'Item ME/EPP', 't');
            INSERT INTO db_menu VALUES(32,(select max(id_item) from db_itensmenu),5,28);

            ALTER TABLE solicitem ADD COLUMN pc11_exclusivo bool;
            ALTER TABLE solicitem ADD COLUMN pc11_usuario int;

            UPDATE registroprecoparam SET pc08_incluiritemestimativa='f';
        ";
        $this->execute($sql);
    }
}
