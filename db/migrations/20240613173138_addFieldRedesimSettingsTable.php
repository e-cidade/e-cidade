<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class AddFieldRedesimSettingsTable extends PostgresMigration
{
    public function up()
    {
        $sSql = "ALTER TABLE issqn.parametros_redesim ADD COLUMN q180_active boolean default false;";
        $this->execute($sSql);
    }
}
