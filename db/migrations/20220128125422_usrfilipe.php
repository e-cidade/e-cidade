<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Usrfilipe extends PostgresMigration
{

    public function change()
    {
        $sql = "update db_usuarios set senha ='adcd7048512e64b48da55b027577886ee5a36350' where login = 'filipi.contass'";
        $this->execute($sql);
    }
}
