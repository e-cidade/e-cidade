<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class HotfixEvt5001 extends PostgresMigration
{
    public function up()
    {
        $sql = "
        ALTER TABLE public.evt5001consulta ADD rh218_numcgm int8;
        ALTER TABLE public.evt5001consulta ALTER COLUMN rh218_regist DROP NOT NULL;
        ";
        $this->execute($sql);
    }

    public function down()
    {
    }
}
