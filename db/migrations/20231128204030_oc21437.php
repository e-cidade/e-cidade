<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc21437 extends PostgresMigration
{
    public function up()
    {
        $sSql = "
        BEGIN;

        UPDATE
            db_syscampo
        SET
            descricao = 'Processo Licitat�rio',
            rotulo = 'Processo Licitat�rio',
            rotulorel = 'Processo Licitat�rio'
        WHERE
            nomecam = 'si06_numeroprc';

        ALTER TABLE adesaoregprecos
        ADD si06_regimecontratacao int default null;

        ALTER TABLE adesaoregprecos
        ADD si06_criterioadjudicacao int default null;

        ALTER TABLE adesaoregprecos ALTER COLUMN si06_modalidade
        DROP NOT NULL;

        ALTER TABLE adesaoregprecos ALTER COLUMN si06_numlicitacao
        DROP NOT NULL;

        ALTER TABLE adesaoregprecos
        ALTER COLUMN si06_modalidade SET DEFAULT null;

        ALTER TABLE adesaoregprecos
        ALTER COLUMN si06_numlicitacao SET DEFAULT null;

        ALTER TABLE adesaoregprecos ALTER COLUMN si06_descontotabela
        DROP NOT NULL;

        ALTER TABLE adesaoregprecos
        ALTER COLUMN si06_descontotabela SET DEFAULT null;

        COMMIT;";
        $this->execute($sSql);
    }
}
