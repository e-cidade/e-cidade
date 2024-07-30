<?php

use Phinx\Migration\AbstractMigration;

class Oc22258 extends AbstractMigration
{

    public function up()
    {
        $sSql = "
               
        BEGIN;
      
        update db_menu set menusequencia = 6 where menusequencia = 5 and id_item = (select id_item from db_itensmenu where descricao = 'PNCP') and modulo = 381;
       
        INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),
         'Anexos de Empenho','Anexos de Empenho','lic1_anexoempenho.php',1,1,'Anexos Empenho','t');
        
        INSERT INTO db_menu VALUES((select id_item from db_itensmenu where descricao = 'PNCP'),(select max(id_item) from db_itensmenu),5,381);

        CREATE TABLE empanexo(
            e100_sequencial          int NOT NULL,
            e100_empenho             int NOT NULL,
            e100_usuario             int NOT NULL,
            e100_instit              int NOT NULL,
            e100_tipoanexo           int NOT NULL,
            e100_datalancamento      date NOT NULL,
            e100_titulo              varchar(255) NOT NULL,
            e100_sequencialpncp      int null,
            e100_sequencialarquivo   int null,
            e100_anexo               oid NOT null,
            PRIMARY KEY (e100_sequencial),
            CONSTRAINT empanexo_e100_empenho_fkey FOREIGN KEY (e100_empenho)
                REFERENCES empenho.empempenho (e60_numemp) MATCH SIMPLE
                ON UPDATE NO ACTION
                ON DELETE NO ACTION
        );


        CREATE SEQUENCE empanexo_e100_sequencial_seq
                    INCREMENT 1
                    MINVALUE 1
                    MAXVALUE 9223372036854775807
                    START 1
                    CACHE 1;
        
        COMMIT;";

        $this->execute($sSql);
    }
}
