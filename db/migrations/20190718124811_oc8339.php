<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc8339 extends PostgresMigration
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
        $sSql = "ALTER TABLE credenciamento ADD COLUMN l205_datacreditem date;
                 ALTER TABLE liclicita ADD COLUMN l20_dtlimitecredenciamento date;
                 ALTER TABLE dispensa182020 DROP CONSTRAINT dispensa182020_reg10_fk;";

        $this->execute($sSql);
    }
}
