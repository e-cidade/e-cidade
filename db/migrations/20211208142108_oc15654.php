<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc15654 extends PostgresMigration
{

    public function up()
    {
        $sql = "begin;
                select fc_startsession();
                update db_syscampo set rotulo = 'Codigo da Licita��o', rotulorel = 'Codigo da Licita��o' where nomecam = 'obr01_licitacao';
                commit";

        $this->execute($sql);
    }
}
