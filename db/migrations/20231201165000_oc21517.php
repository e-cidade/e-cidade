<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class oc21517 extends PostgresMigration
{

    public function up(){
        $this->atualizaTabelaEmpdiaria();
    }

    public function atualizaTabelaEmpdiaria(){
        $sql = "
            BEGIN;

            ALTER TABLE empenho.empdiaria ADD COLUMN e140_qtddiariaspernoite int4;
            ALTER TABLE empenho.empdiaria ADD COLUMN e140_vrldiariaspernoiteuni float8;

            INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'e140_qtddiariaspernoite' ,'int4' ,'Quantidade de Di�rias Pernoite' ,'0' ,'Quantidade de Di�rias Pernoite' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Quantidade de Di�rias Pernoite' );

            INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'empdiaria'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'e140_qtddiariaspernoite'),19, 0);

            INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'e140_vrldiariaspernoiteuni' ,'float8' ,'Valor Unitario da Di�ria Pernoite' ,'' ,'Valor Unitario da Di�ria Pernoite' ,15 ,'false' ,'false' ,'false' ,4 ,'text' ,'Valor Unitario da Di�ria Pernoite' );

            INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'empdiaria'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'e140_vrldiariaspernoiteuni'),20, 0);
            COMMIT;
        ";
        $this->execute($sql);
    }
}
