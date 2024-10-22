<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc18222 extends PostgresMigration
{

    public function up()
    {
        $sql = "ALTER TABLE precoreferencia ADD si01_valorestimado bool default false;";

        $this->execute($sql);
    }
}
