<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc14284 extends PostgresMigration
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
    $sSql = "
        BEGIN;

            ALTER TABLE matparaminstit ADD COLUMN m10_consumo_imediato boolean;

        COMMIT;
        ";
    $this->execute($sSql);
  }
}
