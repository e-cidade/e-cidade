<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc15700 extends PostgresMigration
{

    public function up()
    {
        $sql = "    -- INSERINDO db_sysarquivo
                    INSERT INTO db_sysarquivo (codarq, nomearq, descricao, sigla, dataincl, rotulo, tipotabela, naolibclass, naolibfunc, naolibprog, naolibform) VALUES ((select max(codarq)+1 from db_sysarquivo), 'credenciamentotermo', 'credenciamentotermo', 'l212 ', '2021-09-27', 'credenciamentotermo', 0, false, false, false, false);

                    -- INSERINDO db_syscampo
                    INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l212_sequencial    ', 'int8 ', 'Sequencial'                   ,'Sequencial', 'Sequencial', 19, false, false, false, 1, 'text', 'Sequencial');
                    INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l212_licitacao     ', 'int8 ', 'Licitacao'                    ,'Licitacao', 'Licitacao'  , 19, false, false, false, 1, 'text', 'Licitacao');
                    INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l212_fornecedor    ', 'int8 ', 'Fornecedor'                   ,'0', 'Fornecedor'         , 19, false, false, false, 1, 'text', 'Fornecedor');
                    INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l212_numerotermo   ', 'int4 ', 'N�mero do Termo'              ,'', 'N�mero do Termo'    , 19, false, false, false, 1,  'int4', 'N�mero do Termo');
                    INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l212_dtinicio      ', 'date' , 'Vig�ncia'                     ,'', 'Vig�ncia'            , 16, false, false, false, 0, 'date', 'Vig�ncia');
                    INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l212_dtfim         ', 'date' , 'Vig�ncia Final'               ,'', 'Vig�ncia Final'      , 16, false, false, false, 0, 'date', 'Vig�ncia Final');
                    INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l212_dtpublicacao  ', 'date' , 'Data da Publica��o'           ,'', 'Data da Publica��o'  , 16, false, false, false, 0, 'date', 'Data da Publica��o');
                    INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l212_veiculodepublicacao','text' ,'Veiculo de Publica��o'     ,'', 'Veiculo de Publica��o'  ,500,false, false, false, 0, 'text', 'Veiculo de Publica��o');
                    INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l212_anousu'       ,  'int4' ,'Ano do termo'                  ,'', 'Ano do termo'         ,500,false, false, false, 0, 'text', 'Ano do termo');
                    INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l212_observacao    ', 'text' ,'Observa��o'                    ,'', 'Observa��o'           ,500,false, false, false, 0, 'text', 'Observa��o');


                    -- INSERINDO db_syssequencia

                    -- INSERINDO db_sysarqcamp
                    INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l212_sequencial'), 1, 0);
                    INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l212_licitacao'), 2, 0);
                    INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l212_fornecedor'), 3, 0);
                    INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l212_numerotermo'), 4, 0);
                    INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l212_dtinicio'), 5, 0);
                    INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l212_dtfim'), 6, 0);
                    INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l212_dtpublicacao'), 7, 0);
                    INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l212_veiculodepublicacao'), 8, 0);
                    INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l212_anousu'), 9, 0);
                    INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l212_observacao'), 10, 0);

                    --DROP TABLE:
                    DROP TABLE IF EXISTS credenciamentotermo CASCADE;
                    --Criando drop sequences


                    -- Criando  sequences
                    -- TABELAS E ESTRUTURA

                    -- M�dulo: licitacao
                    CREATE TABLE credenciamentotermo(
                    l212_sequencial          int8,
                    l212_licitacao           int8,
                    l212_fornecedor          int8,
                    l212_numerotermo         int4,
                    l212_dtinicio            date,
                    l212_dtfim               date,
                    l212_dtpublicacao        date,
                    l212_veiculodepublicacao text,
                    l212_anousu              int4,
                    l212_observacao          text,
                    l212_instit             int8);


                    CREATE SEQUENCE credenciamentotermo_l212_sequencial_seq
                    INCREMENT 1
                    MINVALUE 1
                    MAXVALUE 9223372036854775807
                    START 1
                    CACHE 1;

                    -- MENUS

                    --inserindo menu situacao da obra
                    INSERT INTO db_itensmenu VALUES((select max(id_item)+1 from db_itensmenu),'Termo de Credenciamento','Termo de Credenciamento','',1,1,'Termo de Credenciamento','t');
                    INSERT INTO db_menu VALUES(1818,(select max(id_item) from db_itensmenu),7,381);

                    INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Inclus�o','Inclus�o','lic1_credenciamentotermo001.php',1,1,'Inclus�o','t');
                    INSERT INTO db_menu VALUES((select id_item from db_itensmenu where help like'%Termo de Credenciamento%'),(select max(id_item) from db_itensmenu),1,381);

                    INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Altera��o','Altera��o','lic1_credenciamentotermo002.php',1,1,'Altera��o','t');
                    INSERT INTO db_menu VALUES((select id_item from db_itensmenu where help like'%Termo de Credenciamento%'),(select max(id_item) from db_itensmenu),2,381);

                    INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Exclus�o','Exclus�o','lic1_credenciamentotermo003.php',1,1,'Exclus�o','t');
                    INSERT INTO db_menu VALUES((select id_item from db_itensmenu where help like'%Termo de Credenciamento%'),(select max(id_item) from db_itensmenu),3,381);

                    INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Gerar Autoriza��o','Gerar Autoriza��o','lic1_gerarempautorizacedrenciamento001.php',1,1,'Gerar Autoriza��o','t');
                    INSERT INTO db_menu VALUES((select id_item from db_itensmenu where help like'%Termo de Credenciamento%'),(select max(id_item) from db_itensmenu),4,381);

                    ALTER table credenciamentosaldo alter COLUMN l213_acordo DROP NOT NULL;
                    ALTER TABLE credenciamentosaldo ADD COLUMN l213_autori int8;

                    ALTER TABLE empautoriza ADD COLUMN e54_numerotermo int8;


                    ";
        $this->execute($sql);
    }
}
