<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc19724 extends PostgresMigration
{
    public function up()
    {
        $sqlAddNewColumns = "ALTER TABLE public.rsp112023 ADD si113_codco varchar NOT NULL;
                             ALTER TABLE public.rsp212023 ADD si116_codco varchar NOT NULL;
                             ALTER TABLE public.rsp212023 ADD si116_codidentificafr integer";

        $this->execute($sqlAddNewColumns);

    }
}
