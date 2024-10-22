<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc14339hotfix extends PostgresMigration
{
  public function up()
  {
    $sql = <<<SQL
      UPDATE configuracoes.db_itensmenu
      SET descricao='Protocolo Financeiro'
      WHERE descricao ilike 'Protocolo%'
SQL;
    $this->execute($sql);
  }
}
