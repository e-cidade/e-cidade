<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc13452 extends PostgresMigration
{
    public function up()
    {
      $sql = <<<SQL
        BEGIN;
        ALTER TABLE public.disponibilidadecaixa ALTER COLUMN c224_vlrrestoarecolher DROP NOT NULL;
        ALTER TABLE public.disponibilidadecaixa ALTER COLUMN c224_vlrrestoregativofinanceiro DROP NOT NULL;
        ALTER TABLE public.iderp202020 ALTER COLUMN si181_vlrestituiveisrecolher DROP NOT NULL;
        ALTER TABLE public.iderp202020 ALTER COLUMN si181_vlrestituiveisativofinanceiro DROP NOT NULL;
        COMMIT;
SQL;
      $this->execute($sql);

    }
}
