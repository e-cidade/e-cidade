<?php

use Phinx\Migration\AbstractMigration;

class Oc22946 extends AbstractMigration
{

    public function up()
    {
        $sSql = "UPDATE veicmanut v
        SET ve62_valor = (
            SELECT SUM(vi.ve63_vlrtot)
            FROM veicmanutitem vi
            WHERE vi.ve63_veicmanut = v.ve62_codigo
        )
        WHERE EXISTS (
            SELECT 1
            FROM veicmanutitem vi
            WHERE vi.ve63_veicmanut = v.ve62_codigo
        );";
        
        $this->execute($sSql);
    }
}
