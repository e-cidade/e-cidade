<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class InativarUserContass extends PostgresMigration
{

    public function up()
    {
        $sql = "
            update db_usuarios set usuarioativo=2 where login = 'cess.contass';
            update db_usuarios set usuarioativo=2 where login = 'crb.contass';
            update db_usuarios set usuarioativo=2 where login = 'jdrp.contass';
        ";
        $this->execute($sql);
    }
}
