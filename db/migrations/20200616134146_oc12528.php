<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc12528 extends PostgresMigration
{
    public function up()
    {
        $sql = "

        INSERT INTO db_itensmenu VALUES ((SELECT max(id_item)+1 FROM db_itensmenu), 'Altera��o de Documento', 'Altera��o de Documento', 'm4_altdocumento.php', 1, 1, 'Altera��o de Documento', 't');

        INSERT INTO db_menu VALUES (
            (SELECT id_item from db_itensmenu where descricao = 'Manuten��o de dados'),
            (SELECT max(id_item) FROM db_itensmenu),
            (SELECT max(menusequencia)+1 as count FROM db_menu  WHERE id_item = 32),
            (1));
";
        $this->execute($sql);
    }
}
