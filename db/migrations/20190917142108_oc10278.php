<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc10278 extends PostgresMigration
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
        $sql = "insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Execu��o Financeira','Execu��o Financeira','con2_execucaofinanceira001.php',1,1,'Execu��o Financeira','t');
                insert into db_menu values(8595,(SELECT max(id_item) FROM db_itensmenu),4,8251);";

        $this->execute($sql);
    }
}
