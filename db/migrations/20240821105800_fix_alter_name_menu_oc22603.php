<?php

use Phinx\Migration\AbstractMigration;

class FixAlterNameMenuOc22603 extends AbstractMigration
{
    public function change()
    {
        $sql = "
            UPDATE db_itensmenu SET
                descricao = 'Plano de Contratação',
                help = 'Plano de Contratação',
                desctec = 'Plano de Contratação'
            WHERE
                descricao = 'Planos de Contratação';
        ";

        $this->execute($sql);
    }
}
