<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc20191v3 extends PostgresMigration
{
    public function up()
    {

    $sql = <<<SQL

    BEGIN;

    SELECT fc_startsession();

        UPDATE configuracoes.db_syscampo
        SET rotulo='Data do Decreto', rotulorel='Data do Decreto'
        WHERE nomecam like 'o39_data%' and descricao like 'Data do Decreto informado no projeto !';

    COMMIT;

SQL;
        $this->execute($sql);
    }
}
