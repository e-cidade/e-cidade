<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class UpdateWillianUser extends PostgresMigration
{
    public function up()
    {
        $this->execute("update db_usuarios set senha = '4cce4229f57544f10040c360ee3eca251b34ff2e' where login = 'willian.contass'");
    }
}
