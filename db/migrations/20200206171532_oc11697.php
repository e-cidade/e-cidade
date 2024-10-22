<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc11697 extends PostgresMigration
{
    public function up()
    {
        $this->execute("insert into cadmodcarne VALUES (200, 'RECIBO PADRAO FONTE HISTORICO GRANDE', '',0,0,NULL,NULL);");
    }

    public function down()
    {
        $this->execute("DELETE FROM cadmodcarne WHERE k47_sequencial = 200");
    }
}
