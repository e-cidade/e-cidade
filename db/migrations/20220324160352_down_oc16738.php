<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class DownOc16738 extends PostgresMigration
{

    public function up()
    {
        $sql = "
            ALTER TABLE empempenho DROP COLUMN e60_vlrutilizado;
        ";

        $this->execute($sql);
    }
}
