<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc19933 extends PostgresMigration
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

        select fc_startsession();

        begin;

        ALTER TABLE decretopregao ADD COLUMN l201_instit int4;

        update decretopregao set l201_instit = 1;

        insert into db_syscampo values ((select max(codcam)+1 from db_syscampo),'l201_instit','int4','Institui��o',0,'Institui��o',4,'f','f','f',1,'text','Institui��o');
        ";
        $this->execute($sql);
    }
}
