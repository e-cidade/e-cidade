<?php

use Phinx\Migration\AbstractMigration;

class Oc22334 extends AbstractMigration
{
    public function up()
    {
        $this->createCampoInclusaoPromitente();
    }

    private function createCampoInclusaoPromitente()
    {
        $sql = "
        begin;
            ALTER TABLE itbi.paritbi ADD it24_inclusaoautomaticapromitente bool NULL;

            --Inserção dos campos
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'it24_inclusaoautomaticapromitente', 'bool', 'Inclusão Automática do Promitente', 'FALSE' , 'Inclusão Automática do Promitente', 1, false, false, false, 5, 'text', 'Cod. Tabela');
            -- Vínculo tabelas com campos
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES (2362, (select codcam from db_syscampo where nomecam = 'it24_inclusaoautomaticapromitente'), (select max(seqarq)+1 from db_sysarqcamp where codarq = 2362), 0);
        commit;
        ";
        $this->execute($sql);
    }

}
