<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class UpdateField extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL

        BEGIN;
        SELECT fc_startsession();

        ALTER TABLE public.empenhosexcluidos ALTER COLUMN e290_z01_nome TYPE varchar(100) USING e290_z01_nome::varchar;

        COMMIT;

SQL;
    $this->execute($sql);
    }
}
