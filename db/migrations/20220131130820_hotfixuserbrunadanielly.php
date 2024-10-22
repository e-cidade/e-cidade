<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Hotfixuserbrunadanielly extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL

        BEGIN;

        UPDATE protocolo.cgm
            SET z01_cgccpf='10466337671'
            WHERE z01_cgccpf = '12386878651' and z01_nome = 'BRUNA DANIELLY FERREIRA DE SOUZA';

        COMMIT;
SQL;
        $this->execute($sql);
    }
}
