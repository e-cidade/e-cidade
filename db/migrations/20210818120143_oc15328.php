<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc15328 extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
                                /*inserindo novo menu Adjudica��o*/
                INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Adjudica��o', 'Adjudica��o', ' ', 1, 1, 'Adjudica��o de Licita��o', 't');
                INSERT INTO db_menu VALUES(1818,(select max(id_item) from db_itensmenu),9,381);

                INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Inclus�o','Inclus�o','lic_adjudicacaolicitacao001.php',1,1,'Inclus�o','t');
                INSERT INTO db_menu VALUES((select id_item from db_itensmenu where descricao like'%Adjudica��o' ORDER BY id_item desc limit 1),(select max(id_item) from db_itensmenu),1,381);

                INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Altera��o','Altera��o','lic_adjudicacaolicitacao002.php',1,1,'Altera��o','t');
                INSERT INTO db_menu VALUES((select id_item from db_itensmenu where descricao like'%Adjudica��o' ORDER BY id_item desc limit 1),(select max(id_item) from db_itensmenu),2,381);

                INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Exclus�o','Exclus�o','lic_adjudicacaolicitacao003.php',1,1,'Exclus�o','t');
                INSERT INTO db_menu VALUES((select id_item from db_itensmenu where descricao like'%Adjudica��o' ORDER BY id_item desc limit 1),(select max(id_item) from db_itensmenu),3,381);

                /*Removendo not null da tabela homologacao*/
                ALTER table homologacaoadjudica alter COLUMN l202_datahomologacao DROP NOT NULL;

                /*Adicionando nova Situa��o Adjudicada a licitacao*/
                insert into licsituacao values(13,'Adjudicacao','f');

                /*Alterado descricao do menu homologacao*/
                update db_itensmenu set descricao = 'Homologa��o' where id_item in (select id_item from db_itensmenu where descricao like '%Homologa��o Adjudica��o%');

                /*Adicionando campo fornecedor a tabela de itenshomologacao*/
                ALTER TABLE itenshomologacao ADD COLUMN l203_fornecedor int8;
SQL;
        $this->execute($sql);
    }
}
