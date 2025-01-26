<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc19211 extends PostgresMigration
{
    public function up()
    {
        $sSql =
        "BEGIN;

        INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l202_datareferencia','date' ,'Data de Refer�ncia','','Data de Refer�ncia',10,false,false,false,1,'date','Data de Refer�ncia');
        INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo where nomearq = 'homologacaoadjudica'),(select codcam from db_syscampo where nomecam = 'l202_datareferencia'),5,0);

        ALTER TABLE homologacaoadjudica ADD COLUMN l202_datareferencia date;

        UPDATE homologacaoadjudica SET l202_datareferencia = l202_datahomologacao;

        COMMIT;
        ";

        $this->execute($sSql);
    }
}
