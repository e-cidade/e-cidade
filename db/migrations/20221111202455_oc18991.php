<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc18991 extends PostgresMigration
{

    public function up()
    {
        $sql =
            "BEGIN;
            INSERT INTO db_syscampo values ((select max(codcam)+1 from db_syscampo), 've01_codigoant', 'int4', 'C�digo Anterior','0', 'C�digo Anterior', 11, false, false, false, 1, 'text', 'C�digo Anterior');
            COMMIT;
        ";

        $this->execute($sql);
    }
}
