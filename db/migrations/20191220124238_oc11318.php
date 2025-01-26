<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc11318 extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
                update db_syscampo set rotulo = 'N�o permitir reten��o para fora do munic�pio', rotulorel = 'Reten��o p/ Fora do Munic�pio' where nomecam = 'q03_tributacao_municipio';
SQL;

        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
                update db_syscampo set rotulo = 'Reten��o p/ Presta��o Fora do Munic�pio', rotulorel = 'Reten��o p/ Presta��o Fora do Munic�pio' where nomecam = 'q03_tributacao_municipio';
SQL;

        $this->execute($sql);
    }
}
