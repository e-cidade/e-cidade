<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc16496 extends PostgresMigration
{
    public function up()
    {

        $sql = <<<SQL

        BEGIN;

        SELECT fc_startsession();

        ALTER TABLE operacoesdecreditolrf ADD COLUMN c219_dscnumeroinst varchar(3);

        ALTER TABLE dclrf202021 ADD COLUMN si191_dscnumeroinst varchar(3);

        ALTER TABLE dclrf202022 ADD COLUMN si191_dscnumeroinst varchar(3);

        COMMIT;

SQL;
        $this->execute($sql);
    }
}
