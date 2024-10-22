<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc19110 extends PostgresMigration
{
    public function up()
    {

        $sql = <<<SQL

        BEGIN;

        SELECT fc_startsession();

        UPDATE
          orcsuplementacaoparametro
        SET
          o134_orcamentoaprovado = 't'
        WHERE
          o134_anousu = 2022;

        COMMIT;

SQL;
        $this->execute($sql);
    }
}
