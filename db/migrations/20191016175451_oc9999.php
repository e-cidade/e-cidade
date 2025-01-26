<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc9999 extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-PostgresMigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $sql = "

        update db_itensmenu set funcao = 'pat1_benstransf001.php?db_param=int&direta=false' where id_item in (select id_item from db_itensmenu where funcao like '%pat1_benstransf001.php?db_param=int%');
        update db_itensmenu set funcao = 'pat1_benstransf002.php?db_param=int&direta=false' where id_item in (select id_item from db_itensmenu where funcao like '%pat1_benstransf002.php?db_param=int%');
        update db_itensmenu set funcao = 'pat1_benstransf003.php?db_param=int&direta=false' where id_item in (select id_item from db_itensmenu where funcao like '%pat1_benstransf003.php?db_param=int%');

        INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Direta', 'Direta', ' ', 1, 1, 'Transferencias Direta', 't');
        INSERT INTO db_menu VALUES(3626,(select max(id_item) from db_itensmenu),4,439);

        INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Inclus�o','Inclus�o','pat1_benstransf001.php?db_param=int&direta=true',1,1,'Inclus�o','t');
        INSERT INTO db_menu VALUES((select id_item from db_itensmenu where desctec like'%Transferencias Direta'),(select max(id_item) from db_itensmenu),1,439);

        INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Altera��o','Altera��o','pat1_benstransf002.php?db_param=int&direta=true',1,1,'Altera��o','t');
        INSERT INTO db_menu VALUES((select id_item from db_itensmenu where desctec like'%Transferencias Direta'),(select max(id_item) from db_itensmenu),2,439);

        INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Exclus�o','Exclus�o','pat1_benstransf003.php?db_param=int&direta=true',1,1,'Exclus�o','t');
        INSERT INTO db_menu VALUES((select id_item from db_itensmenu where desctec like'%Transferencias Direta'),(select max(id_item) from db_itensmenu),3,439);

        -- insere o campo tipo a tabela benstransf

        ALTER TABLE benstransf ADD t93_tipo int;

        ";
        $this->execute($sql);
    }
}
