<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc18433 extends PostgresMigration
{
    public function up()
    {

        $sql = <<<SQL

        BEGIN;

        SELECT fc_startsession();

         -- Inserindo menu Relatorio de Reten��es Pessoa Jur�dica
         INSERT INTO db_itensmenu VALUES ((SELECT max(id_item)+1 FROM db_itensmenu),'Reten��es Pessoa Jur�dica', 'Reten��es Pessoa Jur�dica','emp2_reinf001.php',1,1,'','t');

        INSERT INTO db_menu VALUES
        ((SELECT id_item FROM db_itensmenu
        WHERE descricao LIKE 'Relatorio de Conferencia'),

        (SELECT max(id_item) FROM db_itensmenu),

        (SELECT max(menusequencia)+1 FROM db_menu
        WHERE id_item =
            (SELECT id_item FROM db_itensmenu WHERE descricao LIKE 'Relatorio de Conferencia')
        AND modulo = 398),

        398);

        COMMIT;

SQL;
        $this->execute($sql);
    }
}
