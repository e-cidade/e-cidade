<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc21264relatoriodesc extends PostgresMigration
{
    public function up()
    {
        $sqlChangeName = "  SELECT fc_startsession();

                            UPDATE db_itensmenu
                            SET descricao = 'Relat�rios de Confer�ncia',
                                help = 'Relat�rio de Confer�ncia do EFD-Reinf',
                                desctec = 'Relat�rio de Confer�ncia do EFD-Reinf'
                            WHERE desctec = 'Reltat�rios de Confer�ncia do EFD-Reinf'";

        $this->execute($sqlChangeName);
    }
}
