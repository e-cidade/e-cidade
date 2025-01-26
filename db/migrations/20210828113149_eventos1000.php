<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Eventos1000 extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
          BEGIN;

            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'eso05_sequencial                ', 'int8', 'Sequencial'				 		  , '0'		, 'Sequencial'			   		      , 19, false, false, true,  1, 'text', 'Sequencial');
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'eso05_codclassificacaotributaria', 'int4', 'C�digo da classifica��o Tributaria', '0'		, 'C�digo da classifica��o Tributaria', 2,  false, false, true,  1, 'text', 'C�digo da classifica��o Tributaria');
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'eso05_indicativocooperativa     ', 'int8', 'Indicativo de cooperativa'		  , 'f'		, 'Indicativo de cooperativa'		  , 1,  false, false, false, 5, 'text', 'Indicativo de cooperativa');
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'eso05_indicativodeconstrutora   ', 'bool', 'Indicativo de Construtora'		  , 'f'		, 'Indicativo de Construtora'		  , 1,  false, false, false, 5, 'text', 'Indicativo de Construtora');
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'eso05_indicativodesoneracao     ', 'bool', 'Indicativo de desonera��o da folha', 'f'		, 'Indicativo de desonera��o da folha', 1,  false, false, false, 5, 'text', 'Indicativo de desonera��o da folha');
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'eso05_microempresa              ', 'bool', 'Indicativo de microempresa - ME '  , 'f'		, 'Indicativo de microempresa - ME '  , 1,  false, false, false, 5, 'text', 'Indicativo de microempresa - ME ');
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'eso05_registroeletronicodeempregados', 'bool', 'registro eletr�nico de empregados ', 'f' , 'registro eletr�nico de empregados ', 1,  false, false, false, 5, 'text', 'registro eletr�nico de empregados ');
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'eso05_cnpjdoentefederativoresp'  , 'varchar', 'CNPJ do Ente Federativo Respons�vel ', 'f' , 'CNPJ do Ente Federativo Respons�vel ', 14,  false, false, false, 5, 'text', 'CNPJ do Ente Federativo Respons�vel ');
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'eso05_instit'				    ,'int8'    ,'Institui��o'					   ,''		 , 'Institui��o'		 				,16	,false, false, false, 1, 'int8', 'Institui��o');

            INSERT INTO db_syssequencia (codsequencia, nomesequencia, incrseq, minvalueseq, maxvalueseq, startseq, cacheseq) VALUES ((select max(codsequencia)+1 from db_syssequencia), 'avaliacaoS1000_eso05_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'eso05_sequencial'), 1, (select codsequencia from db_syssequencia where nomesequencia = 'avaliacaoS1000_eso05_sequencial_seq'));
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'eso05_codclassificacaotributaria'), 2, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'eso05_indicativocooperativa'), 3, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'eso05_indicativodeconstrutora'), 4, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'eso05_indicativodesoneracao'), 5, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'eso05_microempresa'), 6, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'eso05_registroeletronicodeempregados'), 7, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'eso05_cnpjdoentefederativoresp'), 8, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'eso05_instit'), 9, 0);

            --DROP TABLE:
            DROP TABLE IF EXISTS avaliacaoS1000 CASCADE;

            -- TABELAS E ESTRUTURA

            -- M�dulo: esocial
            CREATE TABLE avaliacaoS1000(
            eso05_sequencial                  		int8 NOT NULL ,
            eso05_codclassificacaotributaria  		int4,
            eso05_indicativocooperativa       		int8,
            eso05_indicativodeconstrutora     		bool,
            eso05_indicativodesoneracao       		bool,
            eso05_microempresa                   	bool,
            eso05_registroeletronicodeempregados    int8,
            eso05_cnpjdoentefederativoresp          varchar(14),
			eso05_indicativoprodutorrural 			int8,
			eso05_ideminlei 						text,
			eso05_nrocertificado					int8,
			eso05_dtemitcertificado 				date,
			eso05_dtvalcertificado 					date,
			eso05_protocolorenov 					int8,
			eso05_dtprotocolo 						date,
			eso05_dtpublicacao 						date,
			eso05_veicpublicacao 					text,
			eso05_nropaginadou						text,
			eso05_indicativoacordo 					bool,
            eso05_instit			  				int8);

            -- Criando  sequences
            CREATE SEQUENCE avaliacaoS1000_eso05_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            ALTER TABLE avaliacaoS1000 ADD PRIMARY KEY (eso05_sequencial);

            INSERT INTO db_itensmenu VALUES((select max(id_item)+1 from db_itensmenu),'Eventos','Cadastro de Eventos','',1,1,'Cdastro de Eventos','t');
            INSERT INTO db_menu VALUES(29,(select max(id_item) from db_itensmenu),1,10216);

            INSERT INTO db_itensmenu VALUES((select max(id_item)+1 from db_itensmenu),'S-1000 - Informa��o do Empregador','S-1000 - Informa��o do Empregador','',1,1,'S-1000 - Informa��o do Empregador','t');
            INSERT INTO db_menu VALUES((select id_item from db_itensmenu where help like'%Cadastro de Eventos%'),(select max(id_item) from db_itensmenu),1,10216);


            INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Inclus�o','Inclus�o','eso1_avaliacaos1000001.php',1,1,'Inclus�o','t');
            INSERT INTO db_menu VALUES((select id_item from db_itensmenu where help like'%S-1000 - Informa��o do Empregador%'),(select max(id_item) from db_itensmenu),1,(select id_item from db_modulos where descr_modulo like'%eSocial%'));

            INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Altera��o','Altera��o','eso1_avaliacaos1000002.php',1,1,'Altera��o','t');
            INSERT INTO db_menu VALUES((select id_item from db_itensmenu where help like'%S-1000 - Informa��o do Empregador%'),(select max(id_item) from db_itensmenu),2,(select id_item from db_modulos where descr_modulo like'%eSocial%'));

            INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Exclus�o','Exclus�o','eso1_avaliacaos1000003.php',1,1,'Exclus�o','t');
            INSERT INTO db_menu VALUES((select id_item from db_itensmenu where help like'%S-1000 - Informa��o do Empregador%'),(select max(id_item) from db_itensmenu),3,(select id_item from db_modulos where descr_modulo like'%eSocial%'));
          COMMIT;
SQL;

        $this->execute($sql);
    }
}
