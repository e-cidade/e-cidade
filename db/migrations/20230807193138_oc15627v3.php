<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc15627v3 extends PostgresMigration
{
    public function up()
    {

    $sql = <<<SQL

    BEGIN;

    SELECT fc_startsession();

        INSERT INTO db_itensmenu
            VALUES
            ((select max(id_item)+1 from db_itensmenu), 'Transfer�ncias de Reten��es Or�ament�rias', 'Transfer�ncias de Reten��es Or�ament�rias', 'cai2_transfretorc001.php', 1, 1, 'Transfer�ncias de Reten��es Or�ament�rias', 't');

        INSERT INTO db_menu
            VALUES
            (3951,(select max(id_item) from db_itensmenu),(select max(menusequencia) + 1 from db_menu where id_item = 30 and modulo = 39),39);

    COMMIT;

SQL;
        $this->execute($sql);
    }
}
