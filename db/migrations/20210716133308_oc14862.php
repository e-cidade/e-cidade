<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc14862 extends PostgresMigration
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

              alter table db_config add column orderdepart int;

              insert into db_itensmenu values((select max(id_item)+1 from db_itensmenu),'Global','Global','con4_parametros001.php',1,1,'Global','t');
              insert into db_menu values((select id_item from db_itensmenu where descricao like 'Manuten��o de Par�metros' and funcao = 'con4_parametros001.php'),(select max(id_item) from db_itensmenu),1,1);

              insert into db_itensmenu values((select max(id_item)+1 from db_itensmenu),'Por Institui��o','Por Institui��o','con4_parametrosinstituicao001.php',1,1,'Por Institui��o','t');
              insert into db_menu values((select id_item from db_itensmenu where descricao like 'Manuten��o de Par�metros' and funcao = 'con4_parametros001.php'),(select max(id_item) from db_itensmenu),2,1);

              update db_itensmenu
              set funcao = ''
              where descricao like 'Manuten��o de Par�metros' and funcao = 'con4_parametros001.php';

        ";
        $this->execute($sql);
    }
}
