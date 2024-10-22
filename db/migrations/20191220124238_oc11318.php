<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc11318 extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
                update db_syscampo set rotulo = 'Não permitir retenção para fora do município', rotulorel = 'Retenção p/ Fora do Município' where nomecam = 'q03_tributacao_municipio';
SQL;

        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
                update db_syscampo set rotulo = 'Retenção p/ Prestação Fora do Município', rotulorel = 'Retenção p/ Prestação Fora do Município' where nomecam = 'q03_tributacao_municipio';
SQL;

        $this->execute($sql);
    }
}
