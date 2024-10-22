<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Bugfixcadedital extends PostgresMigration
{

    public function up()
    {
        $sql = "BEGIN;
            UPDATE db_itensmenu SET funcao = 'func_edital.php?notifica=true' WHERE funcao LIKE '%func_edital%';
            COMMIT;";

        $this->execute($sql);

    }
}
