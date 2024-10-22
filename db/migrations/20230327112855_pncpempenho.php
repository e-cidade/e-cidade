<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Pncpempenho extends PostgresMigration
{

    public function up()
    {
        $sql = "
            ALTER TABLE liclicita DROP COLUMN l20_categoriaprocesso RESTRICT;
            alter table liclicita add column l20_categoriaprocesso int4;
        ";
        $this->execute($sql);
    }
}
