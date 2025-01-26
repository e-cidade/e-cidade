<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Addtablerhmotivorescisao extends PostgresMigration
{
    public function up()
    {
        $sql = "
        begin;
            -- INSERE db_sysarquivo
            INSERT INTO db_sysarquivo VALUES((select max(codarq)+1 from db_sysarquivo),'rhmotivorescisao','Motivos de Rescis�o eSocial','rh173','2019-12-21','Motivos de Rescis�o eSocial',0,'f','f','f','f');

            -- INSERE db_sysarqmod
            INSERT INTO db_sysarqmod (codmod, codarq) VALUES ((select codmod from db_sysmodulo where nomemod like '%pessoal%'), (select max(codarq) from db_sysarquivo));

            -- INSERE db_syscampo
            INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'rh173_sequencial','int8' ,'Sequencial','', 'Sequencial' ,11	,false, false, false, 1, 'int8', 'Sequencial');
            INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'rh173_codigo','text' ,'Motivo Rescis�o','', 'Motivo Rescis�o' ,11	,false, false, false, 1, 'text', 'Motivo Rescis�o');
            INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'rh173_descricao' ,'text' ,'Descri��o Rescis�o','', 'Descri��o Rescis�o' ,10	,false, false, false, 0, 'text', 'Descri��o Rescis�o');

            -- INSERE db_sysarqcamp
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'rh173_sequencial'), 1, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'rh173_codigo'), 2, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'rh173_descricao'), 3, 0);

            -- TABELAS E ESTRUTURA
            -- M�dulo: Pessoal
            CREATE TABLE rhmotivorescisao(
                rh173_sequencial		 int8 NOT NULL,
                rh173_codigo			 varchar(14),
                rh173_descricao			 text);

            -- Criando  sequences
            CREATE SEQUENCE rhmotivorescisao_rh173_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            --inserindo menu cadastro de obras
            INSERT INTO db_itensmenu VALUES((select max(id_item)+1 from db_itensmenu),'Cadastro de Motivos de Rescis�o eSocial','Cadastro de Motivos de Rescis�o eSocial','',1,1,'Cadastro de Motivos de Rescis�o eSocial','t');
            INSERT INTO db_menu VALUES(3516,(select max(id_item) from db_itensmenu),11,952);

            INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Inclus�o','Inclus�o','pes1_rhmotivorescisao001.php',1,1,'Inclus�o','t');
            INSERT INTO db_menu VALUES((select id_item from db_itensmenu where help like'%Cadastro de Motivos de Rescis�o eSocial%'),(select max(id_item) from db_itensmenu),1,952);

            INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Altera��o','Altera��o','pes1_rhmotivorescisao002.php',1,1,'Altera��o','t');
            INSERT INTO db_menu VALUES((select id_item from db_itensmenu where help like'%Cadastro de Motivos de Rescis�o eSocial%'),(select max(id_item) from db_itensmenu),2,952);

            INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Exclus�o','Exclus�o','pes1_rhmotivorescisao003.php',1,1,'Exclus�o','t');
            INSERT INTO db_menu VALUES((select id_item from db_itensmenu where help like'%Cadastro de Motivos de Rescis�o eSocial%'),(select max(id_item) from db_itensmenu),3,952);

            insert into rhmotivorescisao values(nextval('rhmotivorescisao_rh173_sequencial_seq'),'01', 'Rescis�o com justa causa, por iniciativa do empregador');
            insert into rhmotivorescisao values(nextval('rhmotivorescisao_rh173_sequencial_seq'),'02', 'Rescis�o sem justa causa, por iniciativa do empregador');
            insert into rhmotivorescisao values(nextval('rhmotivorescisao_rh173_sequencial_seq'),'03', 'Rescis�o antecipada do contrato a termo por iniciativa do empregador');
            insert into rhmotivorescisao values(nextval('rhmotivorescisao_rh173_sequencial_seq'),'04', 'Rescis�o antecipada do contrato a termo por iniciativa do empregado');
            insert into rhmotivorescisao values(nextval('rhmotivorescisao_rh173_sequencial_seq'),'05', 'Rescis�o por culpa rec�proca');
            insert into rhmotivorescisao values(nextval('rhmotivorescisao_rh173_sequencial_seq'),'06', 'Rescis�o por t�rmino do contrato a termo');
            insert into rhmotivorescisao values(nextval('rhmotivorescisao_rh173_sequencial_seq'),'07', 'Rescis�o do contrato de trabalho por iniciativa do empregado');
            insert into rhmotivorescisao values(nextval('rhmotivorescisao_rh173_sequencial_seq'),'10', 'Rescis�o por falecimento do empregado');
            insert into rhmotivorescisao values(nextval('rhmotivorescisao_rh173_sequencial_seq'),'17', 'Rescis�o indireta do contrato de trabalho');
            insert into rhmotivorescisao values(nextval('rhmotivorescisao_rh173_sequencial_seq'),'18', 'Aposentadoria compuls�ria');
            insert into rhmotivorescisao values(nextval('rhmotivorescisao_rh173_sequencial_seq'),'19', 'Aposentadoria por idade');
            insert into rhmotivorescisao values(nextval('rhmotivorescisao_rh173_sequencial_seq'),'20', 'Aposentadoria por idade e tempo de contribui��o');
            insert into rhmotivorescisao values(nextval('rhmotivorescisao_rh173_sequencial_seq'),'23', 'Exonera��o');
            insert into rhmotivorescisao values(nextval('rhmotivorescisao_rh173_sequencial_seq'),'24', 'Demiss�o');
            insert into rhmotivorescisao values(nextval('rhmotivorescisao_rh173_sequencial_seq'),'28', 'T�rmino da cess�o/requisi��o');
            insert into rhmotivorescisao values(nextval('rhmotivorescisao_rh173_sequencial_seq'),'33', 'Rescis�o por acordo entre as partes (art. 484-A da CLT)');
            insert into rhmotivorescisao values(nextval('rhmotivorescisao_rh173_sequencial_seq'),'35', 'Extin��o do contrato de trabalho intermitente');
            insert into rhmotivorescisao values(nextval('rhmotivorescisao_rh173_sequencial_seq'),'39', 'Aposentadoria de servidor estatut�rio, por invalidez');
            insert into rhmotivorescisao values(nextval('rhmotivorescisao_rh173_sequencial_seq'),'40', 'T�rmino do exerc�cio do mandato eletivo');

            ALTER TABLE pessoal.rhpesrescisao ADD rh05_motivo int4 NULL;

            update rhpesrescisao as antigo
            set rh05_motivo =
            case
            when rh05_causa = 10 then 01
            when rh05_causa = 11 then 02
            when rh05_causa = 12 then 06
            when rh05_causa = 20 or rh05_causa = 21 then 07
            when rh05_causa = 60 or rh05_causa = 62 or rh05_causa = 64 then 10
            when rh05_causa = 70 or rh05_causa = 71 then 20
            when rh05_causa = 72 or rh05_causa = 78 or rh05_causa = 79 then 19
            when rh05_causa = 73 or rh05_causa = 74 or rh05_causa = 76 then 39
            when rh05_causa = 75 then 18
            end;

        commit;
        ";
        $this->execute($sql);
    }
}
