<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc17709 extends PostgresMigration
{


    public function up()
    {
        $sql = "update db_itensmenu set descricao = 'Declara��o de Frequ�ncia',
        help = 'Declara��o de Frequ�ncia', desctec = 'Declara��o de Frequ�ncia'
        where descricao = 'Atestado de Frequ�ncia';";
        $this->execute($sql);
    }
}
