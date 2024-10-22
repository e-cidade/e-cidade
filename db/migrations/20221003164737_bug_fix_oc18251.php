<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class BugFixOc18251 extends PostgresMigration
{
    public function up()
    {

        $sSql="
        select fc_startsession();

        begin;

        alter table licitaparam add l12_pncp bool;

        update licitaparam SET l12_pncp = false;

        commit;
        ";

        $this->execute($sSql);

    }
}
