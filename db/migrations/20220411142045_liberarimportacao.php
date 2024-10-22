<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Liberarimportacao extends PostgresMigration
{

    public function up()
    {
        $sql = "
        BEGIN;

            UPDATE db_itensmenu
            SET libcliente = 't'
            WHERE id_item =
                    (SELECT id_item
                    FROM db_itensmenu
                    WHERE funcao = 'vei1_abastimportacao001.php');

        COMMIT;
        ";
        $this->execute($sql);
    }
}
