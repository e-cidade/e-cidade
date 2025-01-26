<?php

use Phinx\Migration\AbstractMigration;

class FixAlterNameMenuOc22603 extends AbstractMigration
{
    public function change()
    {
        $sql = "
            UPDATE db_itensmenu SET
                descricao = 'Plano de Contrata��o',
                help = 'Plano de Contrata��o',
                desctec = 'Plano de Contrata��o'
            WHERE
                descricao = 'Planos de Contrata��o';
        ";

        $this->execute($sql);
    }
}
