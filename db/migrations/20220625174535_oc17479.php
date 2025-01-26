<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc17479 extends PostgresMigration
{
    public function up()
    {
        $sql = "INSERT INTO db_itensmenu VALUES ((select max(id_item) + 1 from db_itensmenu), 'Execu��o do Excesso de Arrecada��o', 'Execu��o do Excesso de Arrecada��o', 'orc2_execucaoexcessoarrecadacao001.php', 1, 1, 'Execu��o do Excesso de Arrecada��o', 't');
        INSERT INTO db_menu VALUES(4149, (select max(id_item) from db_itensmenu), 6, 116);";
        $this->execute($sql);

        $sql = "INSERT INTO db_itensmenu VALUES ((select max(id_item) + 1 from db_itensmenu), 'Execu��o do Superavit', 'Execu��o do Superavit', 'orc2_execucaosuperavit001.php', 1, 1, 'Execu��o do Superavit', 't');
        INSERT INTO db_menu VALUES(4149, (select max(id_item) from db_itensmenu), 7, 116);";
        $this->execute($sql);
    }
}
