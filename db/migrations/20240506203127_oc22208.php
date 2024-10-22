<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22208 extends PostgresMigration
{

    public function up()
    {
        $sql = "
        BEGIN;

        ALTER TABLE liclicita ADD COLUMN l20_dispensaporvalor bool;

        UPDATE liclicita
        SET l20_dispensaporvalor = 'f'
        WHERE l20_tipoprocesso = 1;

        COMMIT;
        ";
        $this->execute($sql);
    }
}
