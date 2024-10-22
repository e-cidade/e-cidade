<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc18251at extends PostgresMigration
{

    public function up()
    {

        $sql="
        BEGIN;
        ALTER TABLE licitaparam ADD l12_pncp bool;
        UPDATE licitaparam SET l12_pncp = false;
        COMMIT;
        ";

    }
}
