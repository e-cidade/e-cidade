<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class FixOc19785 extends PostgresMigration
{
    public function up()
    {
        $sql = "update db_itensmenu set descricao = 'Relat�rios', help = 'Relat�rios',desctec = 'Relat�rios'
        where descricao = 'Relatrios';";
        $this->execute($sql);
    }
}
