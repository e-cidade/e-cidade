<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc17910 extends PostgresMigration
{
    public function up()
    {
        $sql = 'ALTER TABLE "empenho"."empnota" ADD COLUMN "e69_cgmemitente" int4 DEFAULT 1;';
        $this->execute($sql);
    }

    public function down()
    {
        $sql = 'ALTER TABLE "empenho"."empnota" DROP COLUMN "e69_cgmemitente";';
        $this->execute($sql);
    }
}
