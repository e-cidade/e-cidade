<?php

use Phinx\Migration\AbstractMigration;

class FixOc20610 extends AbstractMigration
{

    public function up()
    {
        $sql = "
         begin;
            ALTER TABLE acordos.acordo
            ADD COLUMN ac16_tipopagamento INT NOT NULL DEFAULT 0,
            ADD COLUMN ac16_numparcela INT NULL,
            ADD COLUMN ac16_vlrparcela FLOAT NULL,
            ADD COLUMN ac16_identificadorcipi VARCHAR(512) NULL,
            ADD COLUMN ac16_urlcipi VARCHAR(14) NULL,
            ADD COLUMN ac16_infcomplementares TEXT NULL,
            ADD COLUMN ac16_justificativapncp TEXT NULL;
        ";
        $this->execute($sql);
    }
}
