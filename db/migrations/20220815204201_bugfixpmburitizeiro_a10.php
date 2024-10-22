<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class BugfixpmburitizeiroA10 extends PostgresMigration
{

    public function up()
    {
        $sql = "update db_itensmenu set descricao = 'Transferência de bens por período',help = 'Transferência de bens por período',desctec = 'Transferência de bens por período' where id_item = 3000182;";
        $this->execute($sql);
    }
}
