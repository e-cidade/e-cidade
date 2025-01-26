<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22558 extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL
        BEGIN;
        select fc_startsession();


        UPDATE db_itensmenu set descricao = 'D�vida Consolidada', help = 'D�vida Consolidada', desctec = 'D�vida Consolidada' where  id_item = (select id_item from db_itensmenu where descricao = 'Opera��es de Cr�dito' and help = 'Opera��es de Cr�dito');

        UPDATE db_syscampo set descricao = 'N� de Contrato' ,  rotulo= 'N� de Contrato', rotulorel = 'N� de Contrato' where nomecam = 'op01_numerocontratoopc';

        UPDATE db_syscampo set descricao = 'Data de Assinatura do Contrato' ,  rotulo= 'Data de Assinatura do Contrato', rotulorel = 'Data de Assinatura do Contrato' where nomecam = 'op01_dataassinaturacop';


        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_numeroleiautorizacao', 'varchar(6)                                    ', 'N� da Lei de Autoriza��o', '', 'N� da Lei de Autoriza��o', 6, false, false, false, 0, 'text', 'N� da Lei de Autoriza��o');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_dataleiautorizacao', 'date                                    ', 'Data da Lei de Autoriza��o', '', 'Data da Lei de Autoriza��o', 10, false, false, false, 0, 'text', 'Data da Lei de Autoriza��o');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_valorautorizadoporlei', 'float8                                    ', 'Valor Autorizado por Lei', '', 'Valor Autorizado por Lei', 14, true, false, false, 4, 'text', 'Valor Autorizado por Lei');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_credor', 'varchar(80)                                    ', 'Credor', '', 'Credor', 80, false, false, false, 0, 'text', 'Credor');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_tipocredor', 'int8                                    ', 'Tipo de Credor', 0, 'Tipo de Credor', 2, false, false, false, 1, 'text', 'Tipo de Credor');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_tipolancamento', 'int8                                    ', 'Tipo de Lan�amento', 0, 'Tipo de Lan�amento', 2, false, false, false, 1, 'text', 'Tipo de Lan�amento');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_detalhamentodivida', 'int8                                    ', 'Detalhamento da D�vida', 0, 'Detalhamento da D�vida', 2, false, false, false, 1, 'text', 'Detalhamento da D�vida');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_subtipolancamento', 'int8                                    ', 'Subtipo do Lan�amento', 0, 'Subtipo do Lan�amento', 2, false, false, false, 1, 'text', 'Subtipo do Lan�amento');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_objetocontrato', 'varchar(1000)                                    ', 'Objeto do Contrato', '', 'Objeto do Contrato', 1000, false, false, false, 0, 'text', 'Objeto do Contrato');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_descricaodividaconsolidada', 'varchar(500)                                    ', 'Descri��o da D�vida Consolidada', '', 'Descri��o da D�vida Consolidada', 500, false, false, false, 0, 'text', 'Descri��o da D�vida Consolidada');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_descricaocontapcasp', 'varchar(50)                                    ', 'Descri��o da Conta PCASP', '', 'Descri��o da Conta PCASP', 50, false, false, false, 0, 'text', 'Descri��o da Conta PCASP');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_moedacontratacao', 'varchar(20)                                    ', 'Moeda de Contrata��o', '', 'Moeda de Contrata��o', 20, true, false, false, 0, 'text', 'Moeda de Contrata��o');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_taxajurosdemaisencargos', 'varchar(1000)                                    ', 'Taxa de Juros e Demais Encargos', '', 'Taxa de Juros e Demais Encargos', 1000, true, false, false, 0, 'text', 'Taxa de Juros e Demais Encargos');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_valorcontratacao', 'float8                                    ', 'Valor da contrata��o', '', 'Valor da contrata��o', 14, true, false, false, 4, 'text', 'Valor da contrata��o');

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from db_syscampo), 'op01_dataquitacao', 'date                                    ', 'Data da quita��o', '', 'Data da quita��o', 10, true, false, false, 1, 'text', 'Data da quita��o');


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
