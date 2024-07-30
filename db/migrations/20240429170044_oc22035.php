<?php

use Phinx\Migration\AbstractMigration;

class Oc22035 extends AbstractMigration
{

    public function up()
    {
        $sql = "
        BEGIN;

        UPDATE db_itensmenu
        SET funcao = 'vei1_abastimportacao002.php'
        WHERE funcao = 'vei1_abastimportacao001.php';

        COMMIT;
        ";
        $this->execute($sql);
    }
}
