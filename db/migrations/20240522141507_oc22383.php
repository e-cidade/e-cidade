<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22383 extends PostgresMigration
{
    public function up()
    {
        $sql = "
            alter table licontroleatarppncp add column l215_numataecidade int8;
        ";
        $this->execute($sql);
    }
}
