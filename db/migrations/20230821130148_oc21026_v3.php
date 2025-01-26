<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc21026V3 extends PostgresMigration
{
    public function up()
    {

    $sql = <<<SQL

    BEGIN;

    SELECT fc_startsession();

    UPDATE orcamento.orcsubfuncao
    SET o53_descr='ASSIST�NCIA � PESSOA COM DEFICI�NCIA', o53_finali='ASSIST�NCIA � PESSOA COM DEFICI�NCIA'
    WHERE o53_subfuncao=242;

    UPDATE orcamento.orcsubfuncao
    SET o53_descr='TRANSPORTE HIDROVI�RIO', o53_finali='TRANSPORTE HIDROVI�RIO'
    WHERE o53_subfuncao=784;

    COMMIT;

SQL;
        $this->execute($sql);
    }
}
