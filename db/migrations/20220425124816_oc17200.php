<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc17200 extends PostgresMigration
{

    public function up()
    {
        $sql = "
        UPDATE db_menu SET id_item = 32, menusequencia=457
         WHERE id_item_filho =
         (SELECT id_item FROM db_itensmenu WHERE descricao LIKE '%Apostilamento (Novo)%')
          AND modulo = 8251;";
        $this->execute($sql);
    }
}
