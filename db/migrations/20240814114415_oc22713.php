<?php

use Phinx\Migration\AbstractMigration;

class Oc22713 extends AbstractMigration
{
    public function up()
    {
        $sql = "
            INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Contratações Diretas', 'Contratações Diretas', 'com2_contratacoesdiretas001.php', 1, 1, 'Contratações Diretas', 't');
            INSERT INTO db_menu VALUES(30,(select max(id_item) from db_itensmenu),1019,28);
        ";
        $this->execute($sql);
    }
}
