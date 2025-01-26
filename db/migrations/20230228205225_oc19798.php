<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc19798 extends PostgresMigration
{
    public function up()
    {
        $sqlAddNewColumns = "ALTER TABLE public.caixa132023 ADD si105_codco varchar(4) DEFAULT '0000';
                             ALTER TABLE public.caixa122023 ADD si104_codidentificafr integer DEFAULT null";

        $this->execute($sqlAddNewColumns);
    }
}
