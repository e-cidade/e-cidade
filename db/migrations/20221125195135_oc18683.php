<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc18683 extends PostgresMigration
{
    public function up()
    {

        $sql = <<<SQL

        BEGIN;

        SELECT fc_startsession();

        ALTER TABLE orcsuplementacaoparametro ADD o134_orcamentoaprovado boolean;

	    INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
        VALUES (
          (SELECT max(codcam)+1
           FROM db_syscampo), 'o134_orcamentoaprovado',
                              'boolean',
                              'Or�amento Aprovado',
                              '',
                              'Or�amento Aprovado',
                              1,
                              FALSE,
                              TRUE,
                              FALSE,
                              0,
                              'boolean',
                              'Or�amento Aprovado');

          INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
          VALUES (
          (SELECT codarq
           FROM db_sysarqcamp
           WHERE codcam =
               (SELECT codcam
                FROM db_syscampo
                WHERE nomecam IN ('o134_percentuallimiteloa'))),
          (SELECT codcam
           FROM db_syscampo
           WHERE nomecam = 'o134_orcamentoaprovado'),
          (SELECT max(seqarq)+1
           FROM db_sysarqcamp
           WHERE codcam =
               (SELECT codcam
                FROM db_syscampo
                WHERE nomecam IN ('o134_percentuallimiteloa'))), 0);
        COMMIT;

SQL;
        $this->execute($sql);
    }
}
