<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc14339 extends PostgresMigration
{
    public function up()
    {
      $sql = <<<SQL
      UPDATE configuracoes.db_itensmenu
      SET descricao='Protocolo Financeiro'
      WHERE id_item=4001103;
SQL;
      $this->execute($sql);
    }
}
