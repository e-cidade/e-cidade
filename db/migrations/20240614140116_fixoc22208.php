<?php

use Phinx\Migration\AbstractMigration;

class Fixoc22208 extends AbstractMigration
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
