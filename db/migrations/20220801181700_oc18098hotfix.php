<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc18098hotfix extends PostgresMigration
{
    public function up()
    {

        $sql = <<<SQL

        BEGIN;

        SELECT fc_startsession();

        ALTER TABLE pagordem ADD COLUMN e50_cattrabalhadorremurenacao int4;

        COMMIT;

SQL;
        $this->execute($sql);
    }
}
