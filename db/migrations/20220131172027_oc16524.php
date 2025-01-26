<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc16524 extends PostgresMigration
{

    public function up()
    {
        $sSql = "

        select fc_startsession();

        begin;

        select setval('db_itensmenu_id_item_seq', (select max(id_item) from db_itensmenu));

        insert into db_itensmenu values (nextval('db_itensmenu_id_item_seq'),'Execucao Taxa/Tabela','Execucao Taxa/Tabela','lic2_taxatabela001.php',1,1,'Relat�rio taxa/tabela','t');

        commit;
        ";

        $this->execute($sSql);
    }
}
