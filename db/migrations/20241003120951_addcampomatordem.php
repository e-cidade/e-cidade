<?php

use Phinx\Migration\AbstractMigration;

class Addcampomatordem extends AbstractMigration
{
    public function up()
    {
        $sql = "
            ALTER TABLE matordem ADD COLUMN m51_sequencial int8;
        ";

        $this->execute($sql);
    }
}
