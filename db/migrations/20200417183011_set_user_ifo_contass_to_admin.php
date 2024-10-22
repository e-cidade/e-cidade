<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class SetUserIfoContassToAdmin extends PostgresMigration
{
    public function change()
    {
        $this->execute("update db_usuarios set administrador = 1 where login = 'ifo.contass'");
    }
}
