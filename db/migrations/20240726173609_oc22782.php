<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22782 extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL

        BEGIN;
        SELECT fc_startsession();

        UPDATE db_itensmenu
            SET descricao='Despesa Sa�de (ANEXO V)', help='Despesa Sa�de (ANEXO V)', desctec='Despesa Sa�de (ANEXO V)'
            WHERE descricao = 'ANEXO II SA�DE' AND funcao = 'con2_anexoIIsaude001.php';

        UPDATE db_itensmenu
            SET descricao='Receita Sa�de (ANEXO IV)', help='Receita Sa�de (ANEXO IV)', desctec='Receita Sa�de (ANEXO IV)'
            WHERE descricao = 'ANEXO I SA�DE' AND funcao = 'con2_anexoIsaude001.php';

        ALTER TABLE dadosexercicioanterior ADD c235_valornaoaplicsaude float8 NULL DEFAULT 0;

        INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
                VALUES ((select max(codcam)+1 from db_syscampo), 'c235_valornaoaplicsaude', 'float8', 'Valor n�o aplicado na Sa�de', '' , 'Valor n�o aplicado na Sa�de', 25, false, false, false, 4, 'text', 'Valor n�o aplicado na Sa�de');

        INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
                VALUES (
                    (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'dadosexercicioanterior'),
                    (SELECT codcam FROM db_syscampo WHERE nomecam = 'c235_valornaoaplicsaude'),
                    (SELECT COALESCE(MAX(seqarq), 0) + 1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'dadosexercicioanterior')),0);


        COMMIT;

SQL;
        $this->execute($sql);
    }
}


