<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Fixoc22208 extends PostgresMigration
{

    public function up()
    {
        $sql = "
        BEGIN;

        UPDATE liclicita
        SET l20_dispensaporvalor = 'f';

        COMMIT;
        ";
        $this->execute($sql);
    }
}
