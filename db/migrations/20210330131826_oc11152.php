<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc11152 extends PostgresMigration
{
    public function up()
    {
        $nomeVariavel = "e30_controleprestacao";
        $sql = "
            BEGIN;
            SELECT fc_startsession();

            INSERT INTO db_syscampo
            VALUES (
                (SELECT max(codcam) + 1 FROM db_syscampo), '{$nomeVariavel}', 'bool', 'Permite Controlar o Empenho com Presta��o de Contas',
                'f', 'Controla Empenho de Presta��o de Contas', 1, FALSE, FALSE, FALSE, 5, 'text', 'Controla Empenho de Presta��o de Contas');

            INSERT INTO db_sysarqcamp
            VALUES ((SELECT codarq FROM db_sysarquivo WHERE nomearq = 'empparametro'),
                (SELECT codcam FROM db_syscampo WHERE nomecam = '{$nomeVariavel}'),
                (SELECT max(seqarq) + 1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'empparametro')), 0);

            ALTER TABLE empparametro ADD COLUMN {$nomeVariavel} boolean DEFAULT false;

            COMMIT;";

        $this->execute($sql);
    }
}
