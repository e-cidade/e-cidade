<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22701 extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL

        BEGIN;
        SELECT fc_startsession();

        UPDATE db_itensmenu
            SET descricao='Fundeb (Anexo VIII) (até 2022)', help='Fundeb (Anexo VIII) (até 2022)', desctec='Fundeb (Anexo VIII) (até 2022)'
            WHERE descricao = 'Fundeb (Anexo VIII)' AND funcao = 'con2_anexo8fundeb001.php';

        INSERT INTO db_itensmenu VALUES ((SELECT max(id_item)+1 FROM db_itensmenu),'Fundeb (Anexo VIII)', 'Fundeb (Anexo VIII)','con2_anexoVIIIfundeb001.php',1,1,'','t');

        INSERT INTO db_menu VALUES ((SELECT id_item FROM db_itensmenu WHERE descricao like 'Relat%rios de Acompanhamento'),
        (SELECT max(id_item) FROM db_itensmenu),
        (SELECT max(menusequencia)+1 FROM db_menu WHERE id_item = (SELECT id_item FROM db_itensmenu WHERE descricao like 'Relat%rios de Acompanhamento') AND modulo = 209),209);


        COMMIT;

SQL;
        $this->execute($sql);
    }
}
