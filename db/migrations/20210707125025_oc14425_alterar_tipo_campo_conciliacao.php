<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc14425AlterarTipoCampoConciliacao extends PostgresMigration
{
  public function up()
  {
      $this->execute('ALTER TABLE "caixa"."conciliacaobancaria" ALTER COLUMN "k171_saldo" SET DATA TYPE float8;');
  }

  public function down() {}
}
