<?php

use Phinx\Migration\AbstractMigration;

class Redesim007 extends AbstractMigration
{

    public function up()
    {
        $sql = "
        BEGIN;

        alter table parametros_redesim alter column q180_sequencial set default nextval('issqn.parametros_redesim_q180_sequencial_seq'::text::regclass);

        COMMIT;
        ";
        $this->execute($sql);
    }
}
