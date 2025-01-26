<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc21032 extends PostgresMigration
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

            DELETE
            FROM db_itensfilho
            WHERE id_item =
                    (SELECT id_item
                     FROM db_itensmenu
                     WHERE funcao = 'aco4_homologacaoinclusao001.php');


            DELETE
            FROM db_itensfilho
            WHERE id_item =
                    (SELECT id_item
                     FROM db_itensmenu
                     WHERE funcao = 'aco4_homologacaocancelamento001.php');


            DELETE
            FROM db_itensmenu
            WHERE funcao = 'aco4_homologacaoinclusao001.php';


            DELETE
            FROM db_itensmenu
            WHERE funcao = 'aco4_homologacaocancelamento001.php';


            DELETE
            FROM db_menu
            WHERE id_item =
                    (SELECT id_item
                     FROM db_itensmenu
                     WHERE descricao = 'Finalizar');


            DELETE
            FROM db_itensmenu
            WHERE descricao = 'Finalizar';


        COMMIT;
        ";
        $this->execute($sql);
    }
}
