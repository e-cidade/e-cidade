<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Sigaf1 extends PostgresMigration
{
    public function up()
    {
        $sql = "
            alter table far_parametros add column fa02_i_integracaosigaf bool;
        ";
        $this->execute($sql);
    }
}
