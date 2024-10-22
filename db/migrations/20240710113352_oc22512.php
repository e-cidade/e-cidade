<?php

use Phinx\Migration\AbstractMigration;

class Oc22512 extends AbstractMigration
{
    
    public function up()
    {
        
        $sql = "
        begin;
            ALTER TABLE liclicita
            ADD COLUMN l20_lances BOOL;

            UPDATE liclicita
            SET l20_lances = 'f';

             CREATE TABLE licitacao.licpropostavinc (
                l223_codigo INTEGER PRIMARY KEY,
                l223_liclicita INTEGER,
                l223_fornecedor INTEGER,
                FOREIGN KEY (l223_liclicita) REFERENCES licitacao.liclicita(l20_codigo)
            );


            CREATE TABLE licitacao.licproposta (
                l224_sequencial SERIAL PRIMARY KEY,
                l224_codigo INTEGER ,
                l224_forne INTEGER,
                l224_propitem INTEGER,
                l224_quant DOUBLE PRECISION,
                l224_vlrun DOUBLE PRECISION,
                l224_valor DOUBLE PRECISION,
                l224_porcent DOUBLE PRECISION,
                l224_marca VARCHAR(30),
                FOREIGN KEY (l224_propitem) REFERENCES compras.pcprocitem(pc81_codprocitem),
                FOREIGN KEY (l224_forne) REFERENCES compras.pcorcamforne(pc21_orcamforne)

            );

            INSERT INTO db_sysarquivo VALUES((select max(codarq)+1 from db_sysarquivo),'licproposta','cadastro de proposta','l224','2019-12-21','cadastro de proposta',0,'f','f','f','f');

            INSERT INTO db_sysarqmod (codmod, codarq) VALUES (19, (select max(codarq) from db_sysarquivo));

            INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l224_sequencial'        ,'int8'    ,'l224_sequencial'     ,'', 'l224_sequencial'       ,11    ,false, false, false, 1, 'int8', 'l224_sequencial');
            INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l224_forne'             ,'int8'    ,'l224_forne'          ,'', 'l224_forne'            ,11    ,false, false, false, 1, 'int8', 'l224_forne');
            INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l224_codigo'            ,'int8'    ,'l224_codigo'         ,'', 'l224_codigo'           ,11    ,false, false, false, 1, 'int8', 'l224_codigo');
            INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l224_propitem'          ,'int8'    ,'l224_propitem'       ,'', 'l224_propitem'         ,11    ,false, false, false, 1, 'int8', 'l224_propitem');
            INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l224_quant'             ,'int8'    ,'l224_quant'          ,'', 'l224_quant'            ,11    ,false, false, false, 1, 'int8', 'l224_quant');
            INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l224_vlrun'             ,'numeric' ,'l224_vlrun'          ,'', 'l224_vlrun'            ,16    ,false, false, false, 1, 'numeric', 'l224_vlrun');
            INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l224_valor'             ,'numeric' ,'l224_valor'          ,'', 'l224_valor'           ,16    ,false, false, false, 1, 'numeric', 'l224_valor');
            INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l224_porcent'           ,'numeric' ,'l224_porcent'        ,'', 'l224_porcent'         ,16    ,false, false, false, 1, 'numeric', 'l224_porcent');
            INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l224_marca'             ,'text'    ,'l224_marca'          ,'', 'l224_marca'           ,100   ,false, false, false, 1, 'text', 'l224_marca');
            
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l224_sequencial') , 1, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l224_codigo')     , 2, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l224_forne')      , 3, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l224_propitem')   , 4, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l224_quant')      , 5, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l224_vlrun')      , 6, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l224_valor')      , 7, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l224_porcent')    , 8, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l224_marca')      , 9, 0);  


            INSERT INTO db_sysarquivo VALUES((select max(codarq)+1 from db_sysarquivo),'licpropostavinc','cadastro de proposta vinculada','l224','2019-12-21','cadastro de proposta vinculada',0,'f','f','f','f');

            INSERT INTO db_sysarqmod (codmod, codarq) VALUES (19, (select max(codarq) from db_sysarquivo));

            INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l223_codigo'            ,'int8'    ,'l223_codigo'         ,'', 'l223_codigo'           ,11    ,false, false, false, 1, 'int8', 'l223_codigo');
            INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l223_liclicita'         ,'int8'    ,'l223_liclicita'          ,'', 'l223_liclicita'            ,11    ,false, false, false, 1, 'int8', 'l223_liclicita');
            INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l223_fornecedor'          ,'int8'    ,'l223_fornecedor'       ,'', 'l223_fornecedor'         ,11    ,false, false, false, 1, 'int8', 'l223_fornecedor');


            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l223_codigo')     , 1, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l223_liclicita')      , 2, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l223_fornecedor')   , 3, 0);

            CREATE SEQUENCE licitacao.licpropostavinc_l223_codigo_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Julgamento por Lance', 'Julgamento por Lance', '', 1, 1, 'Julgamento por Lance', 't');
            INSERT INTO db_menu VALUES(1818,(select max(id_item) from db_itensmenu),3,381);

            INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Lançamento de Propostas', 'Lançamento de Propostas', 'lic_propostas.php', 1, 1, 'Lançamento de Propostas', 't');
            INSERT INTO db_menu VALUES((select id_item from db_itensmenu where descricao = 'Julgamento por Lance'),(select max(id_item) from db_itensmenu),1,381);

        commit;
        ";

        $this->execute($sql);
    }
}

