<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class FixOc19785 extends PostgresMigration
{
    public function up()
    {
        $sql = "update db_itensmenu set descricao = 'Relatórios', help = 'Relatórios',desctec = 'Relatórios'
        where descricao = 'Relatrios';";
        $this->execute($sql);
    }
}
