<?php

use Phinx\Migration\AbstractMigration;

class UpdateRedesimLogTable extends AbstractMigration
{
    public function up()
    {
        $sSql = "
            delete from redesim_log;
            ALTER TABLE redesim_log
            ALTER COLUMN q190_json
            SET DATA TYPE jsonb
            USING q190_json::jsonb;
            ";
        $this->execute($sSql);
    }
}
