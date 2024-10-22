<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22709 extends PostgresMigration
{
    public function up()
    {
        $sql = "UPDATE orcparametro SET o50_estrutdespesa = '00.00.00.000.0000.0000.0000000000000.00000000' WHERE o50_anousu >= 2024;";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "UPDATE orcparametro SET o50_estrutdespesa = '00.00.00.000.0000.0000.0000000000000.0000.0000' WHERE o50_anousu >= 2024;";

        $this->execute($sql);
    }
}
