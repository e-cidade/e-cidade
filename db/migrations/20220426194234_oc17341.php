<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc17341 extends PostgresMigration
{
    public function up()
    {

        $sql = <<<SQL

        BEGIN;

        SELECT fc_startsession();

        INSERT INTO orcsuplemtipo
            SELECT 1026 AS o48_tiposup,
                    'CREDITO SUPLEMENTAR - ANULA��O DE DOTA��ES - LAO' AS o48_descr,
                    7 AS o48_coddocsup,
                    51 AS o48_coddocred,
                    0 AS o48_arrecadmaior,
                    'f' AS o48_superavit,
                    7 AS o48_suplcreditoespecial,
                    51 AS o48_redcreditoespecial;

        INSERT INTO orcsuplemtipo
            SELECT 1027 AS o48_tiposup,
                    'CREDITO SUPLEMENTAR - OPERACAO DE CREDITO - LAO' AS o48_descr,
                    52 AS o48_coddocsup,
                    0 AS o48_coddocred,
                    58 AS o48_arrecadmaior,
                    'f' AS o48_superavit,
                    52 AS o48_suplcreditoespecial,
                    0 AS o48_redcreditoespecial;

        INSERT INTO orcsuplemtipo
            SELECT 1028 AS o48_tiposup,
                    'CREDITO SUPLEMENTAR - SUPERAVIT FINANCEIRO - LAO' AS o48_descr,
                    53 AS o48_coddocsup,
                    0 AS o48_coddocred,
                    0 AS o48_arrecadmaior,
                    't' AS o48_superavit,
                    53 AS o48_suplcreditoespecial,
                    0 AS o48_redcreditoespecial;

        INSERT INTO orcsuplemtipo
            SELECT 1029 AS o48_tiposup,
                    'CREDITO SUPLEMENTAR - EXCESSO DE ARRECADA��O - LAO' AS o48_descr,
                    54 AS o48_coddocsup,
                    0 AS o48_coddocred,
                    58 AS o48_arrecadmaior,
                    'f' AS o48_superavit,
                    54 AS o48_suplcreditoespecial,
                    0 AS o48_redcreditoespecial;

        UPDATE orcsuplemtipo SET o48_arrecadmaior = 58 WHERE o48_tiposup = 1002;


        COMMIT;

SQL;
        $this->execute($sql);
    }
}

