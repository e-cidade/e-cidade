<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc20992 extends PostgresMigration
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
    public function up()
    {
        $sql = "
        BEGIN;
            INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Altera��o','Altera��o','ac04_aditaalteracao.php',1,1,'Altera��o aditamento','t');
            INSERT INTO db_menu VALUES(8568,(select max(id_item) from db_itensmenu),6,8251);
        COMMIT;
        ";
        $this->execute($sql);
    }
}
