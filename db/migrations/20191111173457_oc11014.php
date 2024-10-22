<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc11014 extends PostgresMigration
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
        $sql = "update db_syscampo set tamanho = 2 where codcam = (select codcam from db_syscampo where nomecam like '%si03_numapostilamento%');";

        $this->execute($sql);
    }
}
