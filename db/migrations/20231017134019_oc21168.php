<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc21168 extends PostgresMigration
{
    public function up()
    {
        $sSql =
        "BEGIN;

        INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),
        'Alterar Observa��o','Alterar Observa��o','m4_alteraobservacao.php',1,1,'Alterar Observa��o','t');

        INSERT INTO db_menu VALUES(4001478,(select max(id_item) from db_itensmenu),7,1);

        COMMIT;
        ";

        $this->execute($sSql);
    }
}
