<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc14521 extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
        BEGIN;
        SELECT fc_startsession();
        INSERT INTO db_itensmenu VALUES ((SELECT max(id_item)+1 FROM db_itensmenu), 'Exporta��o de Processos Licitat�rios', 'Exporta��o de Processos Licitat�rios', 'con1_exportaprocessos.php', 1, 1, 'Exporta��o de Processos Licitat�rios', 't');

        INSERT INTO db_menu VALUES (1797,
                        (SELECT max(id_item) FROM db_itensmenu),
                        (SELECT max(menusequencia)+1 as count FROM db_menu  WHERE id_item = 1797 and modulo = 381),
                        381);

        COMMIT;
SQL;
        $this->execute($sql);
    }
}
