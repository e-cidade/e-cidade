<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc15672 extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL

        BEGIN;
        SELECT fc_startsession();

        -- ADICIONA CAMPO A TABELA PRECOREFERENCIA
        ALTER TABLE precoreferencia add column si01_tipocotacao int;
        ALTER TABLE precoreferencia add column si01_numcgmcotacao int;
        ALTER TABLE precoreferencia add column si01_tipoorcamento int;
        ALTER TABLE precoreferencia add column si01_numcgmorcamento int;

        COMMIT;

SQL;
        $this->execute($sql);
    }
}
