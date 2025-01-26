<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc19967 extends PostgresMigration
{

    public function up()
    {
        $sql =  "begin;

        INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Lan�amento de Manuten��o','Lan�amento de Manuten��o','',1,1,'Lan�amento de Manuten��o','t');

        INSERT INTO db_menu VALUES(32,(select max(id_item) from db_itensmenu),16,439);


        INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Inclus�o','Inclus�o','pat1_lancmanutencao001.php',1,1,'Inclus�o','t');

        INSERT INTO db_menu VALUES((select id_item from db_itensmenu where descricao ='Lan�amento de Manuten��o'),(select max(id_item) from db_itensmenu),1,439);

        INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Altera��o','Altera��o','pat1_lancmanutencao002.php',1,1,'Altera��o','t');

INSERT INTO db_menu VALUES((select id_item from db_itensmenu where descricao ='Lan�amento de Manuten��o'),(select max(id_item) from db_itensmenu),2,439);

        INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Exclus�o','Exclus�o','pat1_lancmanutencao003.php',1,1,'Exclus�o','t');

        INSERT INTO db_menu VALUES((select id_item from db_itensmenu where descricao ='Lan�amento de Manuten��o'),(select max(id_item) from db_itensmenu),3,439);


        -- INSERE tabela no dicionario de dados
INSERT INTO db_sysarquivo VALUES((select max(codarq)+1 from db_sysarquivo),'bemmanutencao','Cadastro de Manuten��o de Bens','t98','2023-04-18','Cadastro de Manuten��o de Bens',0,'f','f','f','f');

-- INSERE CAMPOS
INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 't98_sequencial','int8' ,'C�d. Sequencial','', 'C�d. Sequencial',11,false, false, false, 1, 'int8', 'C�d. Sequencial');
INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 't98_bem','int8' ,'C�d. do Bem','', 'C�d. do Bem',11,false, false, false, 1, 'int8', 'C�d. do Bem');
INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 't98_data','date' ,'Data','', 'Data',8,false, false, false, 1, 'date', 'Data');
INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 't98_descricao','varchar(500)' ,'Descri��o da Manuten��o','', 'Descri��o da Manuten��o',10,false, false, false, 1, 'varchar(500)', 'Descri��o da Manuten��o');
INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 't98_vlrmanut','float' ,'Valor da Manuten��o','', 'Valor da Manuten��o',15,false, false, false, 1, 'float', 'Valor da Manuten��o');
INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 't98_idusuario','int' ,'Id do Usuario','', 'Usuario',10,false, false, false, 1, 'int', 'Usuario');
INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 't98_dataservidor','date' ,'Data da Manuten��o','', 'Data da Manuten��o',8,false, false, false, 1, 'date', 'Data da Manuten��o');
INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 't98_horaservidor','time' ,'Horario da Manuten��o','', 'Horario da Manuten��o',8,false, false, false, 1, 'time', 'Horario da Manuten��o');
INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 't98_tipo','int4' ,'Tipo da Manuten��o','', 'Tipo da Manuten��o',5,false, false, false, 1, 'int4', 'Tipo da Manuten��o');
INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 't98_manutencaoprocessada','bool' ,'Manuten��o Processada','f', 'Manuten��o Processada',1,false, false, false, 1, 'bool', 'Manuten��o Processada');



-- INSERE VINCULO DO CAMPO COM A TABELA
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 't98_sequencial'), 1, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 't98_bem'), 2, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 't98_data'), 3, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 't98_descricao'), 4, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 't98_vlrmanut'), 5, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 't98_idusuario'), 6, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 't98_dataservidor'), 7, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 't98_horaservidor'), 8, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 't98_tipo'), 9, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 't98_manutencaoprocessada'), 10, 0);


CREATE TABLE bemmanutencao (
	t98_sequencial int8 not null
    ,t98_bem int NOT NULL
    ,t98_data date not null
    ,t98_descricao  varchar(500) not null
    ,t98_vlrmanut float not null
    ,t98_idusuario int not null
    ,t98_dataservidor date not null
    ,t98_horaservidor time not null
    ,t98_tipo int4 not null
    ,t98_manutencaoprocessada bool not null default false);


CREATE SEQUENCE bemmanutencao_t98_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;


ALTER TABLE bemmanutencao ADD PRIMARY KEY (t98_sequencial);

ALTER TABLE bemmanutencao ADD CONSTRAINT bemmanutencao_patrimonio_fk
FOREIGN KEY (t98_bem) REFERENCES bens (t52_bem);


-- INSERE tabela no dicionario de dados
INSERT INTO db_sysarquivo VALUES((select max(codarq)+1 from db_sysarquivo),'manutbensitem','Componentes da manuten��o de bens','t99','2023-04-20','Componentes da manuten��o de bens',0,'f','f','f','f');

-- INSERE CAMPOS
INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 't99_sequencial','int8' ,'C�d. Sequencial','', 'C�d. Sequencial',11,false, false, false, 1, 'int8', 'C�d. Sequencial');
INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 't99_itemsistema','int4' ,'Item do Sistema','', 'Item do Sistema',11,false, false, false, 1, 'int4', 'Item do Sistema');
INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 't99_valor','float' ,'Valor do Item','', 'Valor do Item',8,false, false, false, 1, 'float', 'Valor do Item');
INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 't99_descricao','varchar(200)' ,'Descri��o do Item','', 'Descri��o da Item',200,false, false, false, 1, 'varchar(200)', 'Descri��o do Item');
INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 't99_codpcmater','int4' ,'Codigo pcmater','', 'Codigo pcmater',11,false, false, false, 1, 'int4', 'Codigo pcmater');
INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 't99_codbensdispensatombamento','int4' ,'Codigo bensdispensatombamento','', 'Codigo bensdispensatombamento',11,false, false, false, 1, 'int4', 'Codigo bensdispensatombamento');
INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 't99_codbemmanutencao','int4' ,'Codigo bemmanutencao','', 'Codigo bemmanutencao',11,false, false, false, 1, 'int4', 'Codigo bemmanutencao');

-- INSERE VINCULO DO CAMPO COM A TABELA
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 't99_sequencial'), 1, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 't99_itemsistema'), 2, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 't99_valor'), 3, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 't99_descricao'), 4, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 't99_codpcmater'), 5, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 't99_codbensdispensatombamento'), 6, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 't99_codbemmanutencao'), 7, 0);


CREATE TABLE manutbensitem (
	t99_sequencial int8 not null
    ,t99_itemsistema int4 NOT NULL
    ,t99_valor float not null
    ,t99_descricao  varchar(200) not null
    ,t99_codpcmater int null
    ,t99_codbensdispensatombamento int null
    ,t99_codbemmanutencao int not null);


CREATE SEQUENCE manutbensitem_t99_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;


ALTER TABLE manutbensitem ADD PRIMARY KEY (t99_sequencial);

ALTER TABLE manutbensitem ADD CONSTRAINT manutbensitem_t99_codpcmater_fk FOREIGN KEY(t99_codpcmater) REFERENCES pcmater (pc01_codmater);
ALTER TABLE manutbensitem ADD CONSTRAINT manutbensitem_t99_codbensdispensatombamento_fk FOREIGN KEY(t99_codbensdispensatombamento) REFERENCES bensdispensatombamento (e139_sequencial);
ALTER TABLE manutbensitem ADD CONSTRAINT manutbensitem_t99_codbemmanutencao_fk FOREIGN KEY(t99_codbemmanutencao) REFERENCES bemmanutencao (t98_sequencial);





        commit;";

        $this->execute($sql);
    }
}
