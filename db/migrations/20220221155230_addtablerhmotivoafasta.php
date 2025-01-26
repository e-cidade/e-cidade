<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Addtablerhmotivoafasta extends PostgresMigration
{
    public function up()
    {
        $sql = "
        begin;
            -- INSERE db_sysarquivo
            INSERT INTO db_sysarquivo VALUES((select max(codarq)+1 from db_sysarquivo),'rhmotivoafasta','Motivos de Afastamentos eSocial','rh172','2019-12-21','Motivos de Afastamentos eSocial',0,'f','f','f','f');

            -- INSERE db_sysarqmod
            INSERT INTO db_sysarqmod (codmod, codarq) VALUES ((select codmod from db_sysmodulo where nomemod like '%pessoal%'), (select max(codarq) from db_sysarquivo));

            -- INSERE db_syscampo
            INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'rh172_sequencial','int8' ,'Sequencial','', 'Sequencial' ,11	,false, false, false, 1, 'int8', 'Sequencial');
            INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'rh172_codigo','text' ,'Codigo Afastaemento','', 'Codigo Afastaemento' ,11	,false, false, false, 1, 'text', 'Codigo Afastaemento');
            INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'rh172_descricao' ,'text' ,'Descri��o Afastamento','', 'Descri��o Afastamento' ,10	,false, false, false, 0, 'text', 'Descri��o Afastamento');

            -- INSERE db_sysarqcamp
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'rh172_sequencial'), 1, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'rh172_codigo'), 2, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'rh172_descricao'), 3, 0);

            -- TABELAS E ESTRUTURA
            -- M�dulo: Pessoal
            CREATE TABLE rhmotivoafasta(
                rh172_sequencial		 int8 NOT NULL,
                rh172_codigo			 varchar(14),
                rh172_descricao			 text);

            -- Criando  sequences
            CREATE SEQUENCE rhmotivoafasta_rh172_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            --inserindo menu cadastro de obras
            INSERT INTO db_itensmenu VALUES((select max(id_item)+1 from db_itensmenu),'Cadastro de Motivos de Afastamentos eSocial','Cadastro de Motivos de Afastamentos eSocial','',1,1,'Cadastro de Motivos de Afastamentos eSocial','t');
            INSERT INTO db_menu VALUES(3516,(select max(id_item) from db_itensmenu),11,952);

            INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Inclus�o','Inclus�o','pes1_rhmotivoafasta001.php',1,1,'Inclus�o','t');
            INSERT INTO db_menu VALUES((select id_item from db_itensmenu where help like'%Cadastro de Motivos de Afastamentos eSocial%'),(select max(id_item) from db_itensmenu),1,952);

            INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Altera��o','Altera��o','pes1_rhmotivoafasta002.php',1,1,'Altera��o','t');
            INSERT INTO db_menu VALUES((select id_item from db_itensmenu where help like'%Cadastro de Motivos de Afastamentos eSocial%'),(select max(id_item) from db_itensmenu),2,952);

            INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Exclus�o','Exclus�o','pes1_rhmotivoafasta003.php',1,1,'Exclus�o','t');
            INSERT INTO db_menu VALUES((select id_item from db_itensmenu where help like'%Cadastro de Motivos de Afastamentos eSocial%'),(select max(id_item) from db_itensmenu),3,952);

            insert into rhmotivoafasta values(nextval('rhmotivoafasta_rh172_sequencial_seq'),'01', 'Acidente/doen�a do trabalho');
            insert into rhmotivoafasta values(nextval('rhmotivoafasta_rh172_sequencial_seq'),'03', 'Acidente/doen�a n�o relacionada ao trabalho');
            insert into rhmotivoafasta values(nextval('rhmotivoafasta_rh172_sequencial_seq'),'05', 'Afastamento/licen�a de servidor p�blico prevista em estatuto, sem remunera��o');
            insert into rhmotivoafasta values(nextval('rhmotivoafasta_rh172_sequencial_seq'),'06', 'Aposentadoria por invalidez');
            insert into rhmotivoafasta values(nextval('rhmotivoafasta_rh172_sequencial_seq'),'07', 'Acompanhamento-Licen�a para acompanhamento de membro da fam�lia enfermo');
            insert into rhmotivoafasta values(nextval('rhmotivoafasta_rh172_sequencial_seq'),'10', 'Afastamento/licen�a de servidor p�blico prevista em estatuto, com remunera��o');
            insert into rhmotivoafasta values(nextval('rhmotivoafasta_rh172_sequencial_seq'),'11', 'C�rcere');
            insert into rhmotivoafasta values(nextval('rhmotivoafasta_rh172_sequencial_seq'),'13', 'Cargo eletivo-Candidato a cargo eletivo');
            insert into rhmotivoafasta values(nextval('rhmotivoafasta_rh172_sequencial_seq'),'14', 'Cess�o/Requisi��o');
            insert into rhmotivoafasta values(nextval('rhmotivoafasta_rh172_sequencial_seq'),'17', 'Licen�a maternidade');
            insert into rhmotivoafasta values(nextval('rhmotivoafasta_rh172_sequencial_seq'),'18', 'Licen�a maternidade-Prorroga��o por 60 dias, Lei 11.770/2008 (Empresa Cidad�)');
            insert into rhmotivoafasta values(nextval('rhmotivoafasta_rh172_sequencial_seq'),'19', 'Licen�a maternidade-Afastamento tempor�rio por motivo de aborto n�o criminoso');
            insert into rhmotivoafasta values(nextval('rhmotivoafasta_rh172_sequencial_seq'),'21', 'Licen�a n�o remunerada ou sem vencimento');
            insert into rhmotivoafasta values(nextval('rhmotivoafasta_rh172_sequencial_seq'),'22', 'Mandato eleitoral-Afastamento tempor�rio para o exerc�cio de mandato eleitoral');
            insert into rhmotivoafasta values(nextval('rhmotivoafasta_rh172_sequencial_seq'),'25', 'Mulher v�tima de viol�ncia-Art. 9�, � 2�, inciso II, da Lei 11.340/2006-Lei Maria da Penha');
            insert into rhmotivoafasta values(nextval('rhmotivoafasta_rh172_sequencial_seq'),'29', 'Servi�o militar-Afastamento tempor�rio para prestar servi�o militar obrigat�rio');
            insert into rhmotivoafasta values(nextval('rhmotivoafasta_rh172_sequencial_seq'),'35', 'Licen�a maternidade-Antecipa��o e/ou prorroga��o mediante atestado m�dico');
            insert into rhmotivoafasta values(nextval('rhmotivoafasta_rh172_sequencial_seq'),'36', 'Afastamento tempor�rio de exercente de mandato eletivo para cargo em comiss�o');
            insert into rhmotivoafasta values(nextval('rhmotivoafasta_rh172_sequencial_seq'),'40', 'Exerc�cio em outro �rg�o de servidor ou empregado p�blico cedido');

            alter table afasta ADD COLUMN r45_codigoafasta varchar(14);
            alter table afasta ADD COLUMN r45_mesmadoenca varchar(1);

        commit;
        ";
        $this->execute($sql);
    }
}
