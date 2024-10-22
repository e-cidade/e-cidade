<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc14425AlterarTipoCampo extends PostgresMigration
{
    public function up()
    {
        $this->execute('ALTER TABLE "caixa"."conciliacaobancarialancamento" ALTER COLUMN "k172_valor" SET DATA TYPE float8;');
    }

    public function down() {}
}
