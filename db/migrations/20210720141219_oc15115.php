<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc15115 extends PostgresMigration
{
  public function up()
  {
    $sql = "ALTER TABLE empenho.empautoriza ADD e54_desconto boolean NULL";
    $this->execute($sql);
  }
}
