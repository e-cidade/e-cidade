<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc17942 extends PostgresMigration
{
    public function up()
    {
        $sql = 'DROP INDEX "contabilidade"."idx_fonte_anousu";';
        $this->execute($sql);
        $sql = 'CREATE UNIQUE INDEX idx_fonte_ano ON "contabilidade"."quadrosuperavitdeficit"(c241_fonte, c241_ano, c241_instit);';
        $this->execute($sql);
    }
}
