<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class BugfixpmburitizeiroA10 extends PostgresMigration
{

    public function up()
    {
        $sql = "update db_itensmenu set descricao = 'Transfer�ncia de bens por per�odo',help = 'Transfer�ncia de bens por per�odo',desctec = 'Transfer�ncia de bens por per�odo' where id_item = 3000182;";
        $this->execute($sql);
    }
}
