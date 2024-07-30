<?php

use Phinx\Migration\AbstractMigration;

class AddFieldRedesimSettingsTable extends AbstractMigration
{
    public function up()
    {
        $sSql = "ALTER TABLE issqn.parametros_redesim ADD COLUMN q180_active boolean default false;";
        $this->execute($sSql);
    }
}
