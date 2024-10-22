<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class RemoverRespostasEsocial extends PostgresMigration
{

    public function up()
    {
        $sql = "
        DELETE FROM avaliacaogrupoperguntaresposta;
        DELETE FROM avaliacaogruporesposta;
        DELETE FROM avaliacaoresposta;
        ";

        $this->execute($sql);
    }
}
