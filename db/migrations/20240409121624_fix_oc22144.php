<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class FixOc22144 extends PostgresMigration
{

    public function up()
    {
        $sql = "
            update db_menu set modulo=28 where id_item_filho in (select id_item from db_itensmenu where funcao='com2_capaprocesso001.php');
        ";
        $this->execute($sql);
    }
}
