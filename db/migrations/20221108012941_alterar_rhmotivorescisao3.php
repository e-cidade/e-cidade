<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class AlterarRhmotivorescisao3 extends PostgresMigration
{
    public function up()
    {
        $sql = "
        UPDATE rhpesrescisao SET rh05_motivo = 7 WHERE rh05_motivo IN (24);

        DELETE FROM rhmotivorescisao WHERE rh173_codigo IN ('24');
        ";
        $this->execute($sql);
    }

    public function down()
    {

    }
}
