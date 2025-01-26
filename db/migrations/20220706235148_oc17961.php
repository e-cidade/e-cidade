<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc17961 extends PostgresMigration
{
    public function up()
    {
        $sql = 'ALTER TABLE "empenho"."empnota" ALTER COLUMN "e69_cgmemitente" SET DEFAULT 0;';
        $this->execute($sql);
        $sql = 'UPDATE "empenho"."empnota" SET "e69_cgmemitente" = 0 WHERE "e69_cgmemitente" = 1;';
        $this->execute($sql);
    }
}
