<?php

use Phinx\Migration\AbstractMigration;

class Oc22383 extends AbstractMigration
{
    public function up()
    {
        $sql = "
            alter table licontroleatarppncp add column l215_numataecidade int8;
        ";
        $this->execute($sql);
    }
}
