<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22558 extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL
        BEGIN;
        select fc_startsession();


        UPDATE db_itensmenu set descricao = 'Dívida Consolidada', help = 'Dívida Consolidada', desctec = 'Dívida Consolidada' where  id_item = (select id_item from db_itensmenu where descricao = 'Operações de Crédito' and help = 'Operações de Crédito');

        UPDATE db_syscampo set descricao = 'Nº de Contrato' ,  rotulo= 'Nº de Contrato', rotulorel = 'Nº de Contrato' where nomecam = 'op01_numerocontratoopc';

        UPDATE db_syscampo set descricao = 'Data de Assinatura do Contrato' ,  rotulo= 'Data de Assinatura do Contrato', rotulorel = 'Data de Assinatura do Contrato' where nomecam = 'op01_dataassinaturacop';


        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_numeroleiautorizacao', 'varchar(6)                                    ', 'Nº da Lei de Autorização', '', 'Nº da Lei de Autorização', 6, false, false, false, 0, 'text', 'Nº da Lei de Autorização');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_dataleiautorizacao', 'date                                    ', 'Data da Lei de Autorização', '', 'Data da Lei de Autorização', 10, false, false, false, 0, 'text', 'Data da Lei de Autorização');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_valorautorizadoporlei', 'float8                                    ', 'Valor Autorizado por Lei', '', 'Valor Autorizado por Lei', 14, true, false, false, 4, 'text', 'Valor Autorizado por Lei');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_credor', 'varchar(80)                                    ', 'Credor', '', 'Credor', 80, false, false, false, 0, 'text', 'Credor');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_tipocredor', 'int8                                    ', 'Tipo de Credor', 0, 'Tipo de Credor', 2, false, false, false, 1, 'text', 'Tipo de Credor');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_tipolancamento', 'int8                                    ', 'Tipo de Lançamento', 0, 'Tipo de Lançamento', 2, false, false, false, 1, 'text', 'Tipo de Lançamento');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_detalhamentodivida', 'int8                                    ', 'Detalhamento da Dívida', 0, 'Detalhamento da Dívida', 2, false, false, false, 1, 'text', 'Detalhamento da Dívida');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_subtipolancamento', 'int8                                    ', 'Subtipo do Lançamento', 0, 'Subtipo do Lançamento', 2, false, false, false, 1, 'text', 'Subtipo do Lançamento');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_objetocontrato', 'varchar(1000)                                    ', 'Objeto do Contrato', '', 'Objeto do Contrato', 1000, false, false, false, 0, 'text', 'Objeto do Contrato');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_descricaodividaconsolidada', 'varchar(500)                                    ', 'Descrição da Dívida Consolidada', '', 'Descrição da Dívida Consolidada', 500, false, false, false, 0, 'text', 'Descrição da Dívida Consolidada');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_descricaocontapcasp', 'varchar(50)                                    ', 'Descrição da Conta PCASP', '', 'Descrição da Conta PCASP', 50, false, false, false, 0, 'text', 'Descrição da Conta PCASP');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_moedacontratacao', 'varchar(20)                                    ', 'Moeda de Contratação', '', 'Moeda de Contratação', 20, true, false, false, 0, 'text', 'Moeda de Contratação');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_taxajurosdemaisencargos', 'varchar(1000)                                    ', 'Taxa de Juros e Demais Encargos', '', 'Taxa de Juros e Demais Encargos', 1000, true, false, false, 0, 'text', 'Taxa de Juros e Demais Encargos');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_valorcontratacao', 'float8                                    ', 'Valor da contratação', '', 'Valor da contratação', 14, true, false, false, 4, 'text', 'Valor da contratação');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_dataquitacao', 'date                                    ', 'Data da quitação', '', 'Data da quitação', 10, true, false, false, 1, 'text', 'Data da quitação');


        ALTER TABLE db_operacaodecredito ADD COLUMN op01_numeroleiautorizacao VARCHAR(6);
        ALTER TABLE db_operacaodecredito ADD COLUMN op01_dataleiautorizacao DATE;
        ALTER TABLE db_operacaodecredito ADD COLUMN op01_valorautorizadoporlei FLOAT8;
        ALTER TABLE db_operacaodecredito ADD COLUMN op01_credor INT4;
        ALTER TABLE db_operacaodecredito ADD COLUMN op01_tipocredor INT8;
        ALTER TABLE db_operacaodecredito ADD COLUMN op01_tipolancamento INT8;
        ALTER TABLE db_operacaodecredito ADD COLUMN op01_detalhamentodivida INT8;
        ALTER TABLE db_operacaodecredito ADD COLUMN op01_subtipolancamento INT8;
        ALTER TABLE db_operacaodecredito ADD COLUMN op01_objetocontrato VARCHAR(1000);
        ALTER TABLE db_operacaodecredito ADD COLUMN op01_descricaodividaconsolidada VARCHAR(500);
        ALTER TABLE db_operacaodecredito ADD COLUMN op01_descricaocontapcasp VARCHAR(50);
        ALTER TABLE db_operacaodecredito ADD COLUMN op01_moedacontratacao VARCHAR(20);
        ALTER TABLE db_operacaodecredito ADD COLUMN op01_taxajurosdemaisencargos VARCHAR(1000);
        ALTER TABLE db_operacaodecredito ADD COLUMN op01_valorcontratacao FLOAT8;
        ALTER TABLE db_operacaodecredito ADD COLUMN op01_dataquitacao DATE;
        ALTER TABLE db_operacaodecredito ADD COLUMN op01_instituicao INT8 DEFAULT 1;

        COMMIT;
        SQL;
        $this->execute($sql);
    }

}
