<?php

use Phinx\Migration\AbstractMigration;

class Oc20610 extends AbstractMigration
{
    public function up()
    {
        $querySQL = "
            ALTER TABLE acordos.acordo
            ADD COLUMN ac16_tipopagamento INT NOT NULL DEFAULT 0,
            ADD COLUMN ac16_numparcela INT NULL,
            ADD COLUMN ac16_vlrparcela FLOAT NULL,
            ADD COLUMN ac16_identificadorcipi VARCHAR(512) NULL,
            ADD COLUMN ac16_urlcipi VARCHAR(14) NULL,
            ADD COLUMN ac16_infcomplementares TEXT NULL,
            ADD COLUMN ac16_justificativapncp TEXT NULL,
        ";
        
        $this->execute($querySQL);
    }

    public function down()
    {
        $querySQL = "
            ALTER TABLE acordos.acordo
            DROP COLUMN ac16_tipopagamento,
            DROP COLUMN ac16_numparcela,
            DROP COLUMN ac16_vlrparcela,
            DROP COLUMN ac16_identificadorcipi,
            DROP COLUMN ac16_urlcipi,
            DROP COLUMN ac16_infcomplementares,
            DROP COLUMN ac16_justificativapncp,
        ";
        
        $this->execute($querySQL);
    }
}
