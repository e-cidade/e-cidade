<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc18074v3 extends PostgresMigration
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


                begin;

                INSERT INTO db_itensmenu VALUES((select max(id_item)+1 from db_itensmenu),'Manuten��o de Registro de Pre�o','Manuten��o de Registro de Pre�o','com4_alterarabertestimregistro.php',1,1,'Manuten��o de Registro de Pre�o','t');
                INSERT INTO db_menu VALUES(7941,(select max(id_item) from db_itensmenu),(select max(menusequencia)+1 from db_menu where id_item = 7941 and modulo = 28),28);

                commit;

              ";

        $this->execute($sql);

    }
}
