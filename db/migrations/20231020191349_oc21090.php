<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc21090 extends PostgresMigration
{
    public function up()
    {
        $sSql = "BEGIN;
        INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'si01_cotacaoitem','text' ,'Cota��o por Item' ,'', 'Cota��o por Item',22,false, false, false, 1, 'text', 'Cota��o por Item');
        COMMIT;";
        $this->execute(($sSql));
    }
}
