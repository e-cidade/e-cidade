<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22066 extends PostgresMigration
{
    public function up()
    {
        $sSql = "
        BEGIN;

        INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),
        'Or�amento','Or�amento','m4_orcamento.php',1,1,'Or�amento','t');

        INSERT INTO db_menu VALUES(4001375,(select max(id_item) from db_itensmenu),(select max(menusequencia) from db_menu where id_item = 4001375 and modulo = 1),1);

        COMMIT;";

        $this->execute($sSql);
    }
}
