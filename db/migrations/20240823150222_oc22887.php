<?php

use Phinx\Migration\AbstractMigration;

class Oc22887 extends AbstractMigration
{
    public function up()
    {
        // Truncar os dados para 255 caracteres
        $querySQL = "
            UPDATE acordos.acordo
            SET ac16_justificativapncp = LEFT(ac16_justificativapncp, 255)
        ";
        $this->execute($querySQL);

        // Alterar a coluna para VARCHAR(255)
        $querySQL = "
            ALTER TABLE acordos.acordo
            ALTER COLUMN ac16_justificativapncp TYPE VARCHAR(255)
        ";
        $this->execute($querySQL);
    }

    public function down()
    {
        // Reverter a coluna para TEXT
        $querySQL = "
            ALTER TABLE acordos.acordo
            ALTER COLUMN ac16_justificativapncp TYPE TEXT
        ";
        $this->execute($querySQL);
    }
}
