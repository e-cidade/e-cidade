<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22272 extends PostgresMigration
{
    public function up()
    {
        $sql = "
        begin;
        UPDATE configuracoes.db_syscampo set descricao = 'Departamento de Origem',rotulo = 'Departamento de Origem',rotulorel = 'Departamento de Origem' where nomecam = 'm91_depto';

        commit;
        ";
        $this->execute($sql);
    }
}
