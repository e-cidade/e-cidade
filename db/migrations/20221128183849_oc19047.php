<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc19047 extends PostgresMigration
{

    public function up()
    {
        $sql= "BEGIN;
        ALTER TABLE liclicita ADD l20_amparolegal INT8;
        COMMIT;
        ";

        $this->execute($sql);
    }
}
