<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc16879 extends PostgresMigration
{
    public function up()
    {

        $sql = <<<SQL

        BEGIN;

        SELECT fc_startsession();

        UPDATE configuracoes.db_syscampo
        SET descricao='C�digo STN Anterior' , rotulo = 'C�digo STN Anterior', rotulorel = 'C�digo STN Anterior'
        WHERE nomecam = 'o15_codstn';

        ALTER TABLE orctiporec ADD COLUMN o15_codstnnovo int4;

        INSERT INTO db_syscampo
                (codcam,nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
                VALUES
                ((select max(codcam)+1 from db_syscampo),'o15_codstnnovo', 'int', 'C�digo STN', '0', 'C�digo STN', 4, false, false, false, 1, 'text', 'C�digo STN');

        INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
        VALUES (
            (select codarq from db_sysarqcamp where codcam = (select codcam from db_syscampo where nomecam in ('o15_codigo') limit 1) order by codarq limit 1)
            , (select codcam from db_syscampo where nomecam = 'o15_codstnnovo')
            , (select max(seqarq)+1 from db_sysarqcamp where codarq = (select codarq from db_sysarqcamp where codcam = (select codcam from db_syscampo where nomecam in ('o15_codigo') limit 1) order by codarq limit 1))
            , (select max(codsequencia)+1 from db_sysarqcamp where codarq = (select codarq from db_sysarqcamp where codcam = (select codcam from db_syscampo where nomecam in ('o15_codigo') limit 1) order by codarq limit 1)));

        COMMIT;

SQL;
        $this->execute($sql);
    }
}
