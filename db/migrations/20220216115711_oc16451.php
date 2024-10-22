<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc16451 extends PostgresMigration
{

    public function up()
    {
        $sSql = "alter table ralic102022 add column si180_leidalicitacao int;
        alter table ralic102022 add column si180_mododisputa int;

        alter table redispi102022 add column si183_leidalicitacao int;
        alter table redispi102022 add column si183_regimeexecucaoobras int;";

        $this->execute($sSql);
    }
}
