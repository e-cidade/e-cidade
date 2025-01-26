<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc15454 extends PostgresMigration
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
        $sSQL = <<<SQL

        SELECT fc_startsession();

        ALTER TABLE db_config ADD COLUMN db21_usadebitoitbi boolean default false;

SQL;

        $this->execute($sSQL);
    }
}
