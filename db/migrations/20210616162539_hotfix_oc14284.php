<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class HotfixOc14284 extends PostgresMigration
{
    public function up()
    {
      $this->execute("ALTER TABLE material.matparaminstit ALTER COLUMN m10_consumo_imediato SET DEFAULT false;");
    }
}
