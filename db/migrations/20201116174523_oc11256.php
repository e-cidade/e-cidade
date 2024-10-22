<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc11256 extends PostgresMigration
{
    public function up()
    {
      $this->execute("ALTER TABLE public.projecaoatuarial20 ADD si169_data date NULL");
      $this->execute("ALTER TABLE public.parpps202020 ADD si155_dtavaliacao date NULL;");
    }
}
